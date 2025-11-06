<?php
session_start();
include_once '../config/db.php';
include_once '../includes/functions.php';
include_once '../includes/log_action.php';

function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

if (isset($_POST['register'])) {
    $username = sanitize($_POST['username']);
    $email    = sanitize($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered. Please login instead.'); window.location.href='../public/login.php';</script>";
        exit();
    }
    $check->close();

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 600; 

    $_SESSION['pending_user'] = [
        'username' => $username,
        'email'    => $email,
        'password' => $hashedPassword,
        'role'     => $role
    ];

    sendEmailOTP($email, $otp);

    log_action('Registration Attempt', "OTP sent to {$email}");

    header("Location: ../public/verify_otp.php?type=register");
    exit();
}

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

    if ($enteredOtp == $savedOtp) {
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);

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

    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 600; 
    $_SESSION['login_user'] = $user;

    sendEmailOTP($email, $otp);
    log_action('Login Attempt', "OTP sent to {$email}");

    header("Location: ../public/verify_otp.php?type=login");
    exit();
}

if (isset($_GET['logout'])) {
    if (isset($_SESSION['user_id'])) {
        log_action('Logout', 'User logged out.');
    }

    session_unset();
    session_destroy();
    header("Location: ../public/login.php");
    exit();
}

if (isset($_POST['forgot_password'])) {
    $email = sanitize($_POST['email']);

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

    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 600; 
    $_SESSION['reset_user'] = $user;

    sendEmailOTP($email, $otp);
    log_action('Password Reset Attempt', "OTP sent to {$email}");

    header("Location: ../public/verify_otp.php?type=reset");
    exit();
}

if (isset($_POST['verify_reset_otp'])) {
    $enteredOtp = trim($_POST['otp'] ?? '');
    $savedOtp   = $_SESSION['otp'] ?? '';
    $expiry     = $_SESSION['otp_expiry'] ?? 0;

    if (time() > $expiry) {
        echo "<script>alert('OTP expired. Try again.'); window.location.href='../public/forgot_password.php';</script>";
        exit();
    }

    if ($enteredOtp == $savedOtp && isset($_SESSION['reset_user'])) {
        $_SESSION['otp_verified'] = true;

        unset($_SESSION['otp'], $_SESSION['otp_expiry']);

        header("Location: ../public/reset_form.php");
        exit();
    } else {
        echo "<script>alert('Invalid OTP. Try again.'); window.location.href='../public/verify_otp.php?type=reset';</script>";
        exit();
    }
}


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
