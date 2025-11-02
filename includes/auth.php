<?php
session_start();
include_once '../config/db.php';
include_once '../includes/functions.php';
include_once '../includes/log_action.php';

/* ------------------ HELPER FUNCTION ------------------ */
function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

/* ------------------ REGISTRATION HANDLER ------------------ */
if (isset($_POST['register'])) {
    $username = sanitize($_POST['username']);
    $email    = sanitize($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // ✅ Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered. Please login instead.'); window.location.href='../public/login.php';</script>";
        exit();
    }
    $check->close();

    // ✅ Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // ✅ Generate OTP
    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 600; // 10 minutes expiry

    // ✅ Temporarily store registration details
    $_SESSION['pending_user'] = [
        'username' => $username,
        'email'    => $email,
        'password' => $hashedPassword,
        'role'     => $role
    ];

    // ✅ Send OTP email
    sendEmailOTP($email, $otp);

    // ✅ Log the registration attempt
    log_action('Registration Attempt', "OTP sent to {$email}");

    // ✅ Redirect to OTP verification
    header("Location: ../public/verify_otp.php?type=register");
    exit();
}

/* ------------------ OTP VERIFICATION HANDLER ------------------ */
if (isset($_POST['verify_otp'])) {
    $enteredOtp = trim($_POST['otp'] ?? '');
    $savedOtp   = $_SESSION['otp'] ?? '';
    $expiry     = $_SESSION['otp_expiry'] ?? 0;

    if (empty($enteredOtp)) {
        echo "<script>alert('Please enter your OTP.'); window.history.back();</script>";
        exit();
    }

    if (time() > $expiry) {
        echo "<script>alert('OTP expired. Please register or login again.'); window.location.href='../public/login.php';</script>";
        exit();
    }

    // ✅ FIXED: Use loose comparison instead of strict
    if ($enteredOtp == $savedOtp) {
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);

        // ✅ Registration OTP verified
        if (isset($_SESSION['pending_user'])) {
            $user = $_SESSION['pending_user'];

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $user['username'], $user['email'], $user['password'], $user['role']);
            $stmt->execute();
            $stmt->close();

            log_action('Registration Success', "User {$user['email']} successfully registered.");
            unset($_SESSION['pending_user']);

            echo "<script>alert('Registration successful! Please login.'); window.location.href='../public/login.php';</script>";
            exit();
        }

        // ✅ Login OTP verified
        if (isset($_SESSION['login_user'])) {
            $user = $_SESSION['login_user'];

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['role']     = $user['role'];

            unset($_SESSION['login_user']);

            log_action('Login Success', "User {$user['email']} logged in.");

            if ($user['role'] === 'admin') {
                header("Location: ../public/dashboard_admin.php");
            } else {
                header("Location: ../public/dashboard_user.php");
            }
            exit();
        }
    } else {
        echo "<script>alert('Invalid OTP. Please try again.'); window.location.href='../public/verify_otp.php';</script>";
        exit();
    }
}

/* ------------------ LOGIN HANDLER ------------------ */
if (isset($_POST['login'])) {
    $email    = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('No account found with this email.'); window.location.href='../public/login.php';</script>";
        exit();
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    if (!password_verify($password, $user['password'])) {
        log_action('Login Failed', "Wrong password entered for {$email}");
        echo "<script>alert('Incorrect password.'); window.location.href='../public/login.php';</script>";
        exit();
    }

    // ✅ Generate and send OTP for login
    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 600; // 10 minutes
    $_SESSION['login_user'] = $user;

    sendEmailOTP($email, $otp);
    log_action('Login Attempt', "OTP sent to {$email}");

    header("Location: ../public/verify_otp.php?type=login");
    exit();
}

/* ------------------ LOGOUT HANDLER ------------------ */
if (isset($_GET['logout'])) {
    if (isset($_SESSION['user_id'])) {
        log_action('Logout', 'User logged out.');
    }

    session_unset();
    session_destroy();
    header("Location: ../public/login.php");
    exit();
}

/* ------------------ FORGOT PASSWORD HANDLER ------------------ */
if (isset($_POST['forgot_password'])) {
    $email = sanitize($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('No account found with this email.'); window.location.href='../public/forgot_password.php';</script>";
        exit();
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Generate OTP for password reset
    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 600; // 10 minutes
    $_SESSION['reset_user'] = $user;

    // Send email OTP
    sendEmailOTP($email, $otp);
    log_action('Password Reset Attempt', "OTP sent to {$email}");

    // Redirect to verify OTP page
    header("Location: ../public/verify_otp.php?type=reset");
    exit();
}

/* ------------------ PASSWORD RESET VERIFICATION ------------------ */
if (isset($_POST['verify_reset_otp'])) {
    $enteredOtp = trim($_POST['otp'] ?? '');
    $savedOtp   = $_SESSION['otp'] ?? '';
    $expiry     = $_SESSION['otp_expiry'] ?? 0;

    if (time() > $expiry) {
        echo "<script>alert('OTP expired. Try again.'); window.location.href='../public/forgot_password.php';</script>";
        exit();
    }

    if ($enteredOtp == $savedOtp && isset($_SESSION['reset_user'])) {
        // ✅ Mark OTP verified so reset_password.php works
        $_SESSION['otp_verified'] = true;

        // ✅ Clear OTP data
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);

        // OTP valid — allow user to reset password
        header("Location: ../public/reset_password.php");
        exit();
    } else {
        echo "<script>alert('Invalid OTP. Try again.'); window.location.href='../public/verify_otp.php?type=reset';</script>";
        exit();
    }
}


/* ------------------ PASSWORD UPDATE HANDLER ------------------ */
if (isset($_POST['reset_password'])) {
    $newPass = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($newPass !== $confirm) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    if (!isset($_SESSION['reset_user']['id'])) {
        echo "<script>alert('Session expired. Please try again.'); window.location.href='../public/forgot_password.php';</script>";
        exit();
    }

    $userId = $_SESSION['reset_user']['id'];
    $hashed = password_hash($newPass, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed, $userId);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['reset_user']);
    echo "<script>alert('Password updated successfully!'); window.location.href='../public/login.php';</script>";
    exit();
}


?>
