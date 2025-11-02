
<?php
session_start();
include_once '../includes/functions.php';

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true || !isset($_SESSION['reset_user'])) {
    // either no OTP verified or session expired
    echo "<script>alert('Session expired or OTP not verified. Please try again.'); window.location.href='forgot_password.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Reset Password</title></head>
<body>
<h2>Reset Password</h2>
<form method="POST" action="../includes/auth.php">
    <input type="password" name="new_password" placeholder="New password" required><br><br>
    <input type="password" name="confirm_password" placeholder="Confirm password" required><br><br>
    <button type="submit" name="reset_password">Reset Password</button>
</form>
</body>
</html>
