
<?php 
include_once '../includes/functions.php';
include('../config/db.php');
include('../includes/log_action.php');
?>
<!DOCTYPE html><html><head><meta charset='utf-8'><title>Login</title></head><body>
<h2>Login</h2>
<form method="POST" action="../includes/auth.php">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" name="login">Login</button>
</form>
<p><a href="forgot_password.php">Forgot Password?</a></p>
<p><a href="register.php">Create new account</a></p>
</body></html>