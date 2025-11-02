
<?php
session_start();
include_once '../includes/functions.php';

// Prevent direct access
if (!isset($_SESSION['otp'])) {
    echo "<script>alert('No OTP found. Please start registration or login again.'); window.location.href='login.php';</script>";
    exit();
}

$email = $_SESSION['pending_user']['email'] ?? ($_SESSION['login_user']['email'] ?? 'your registered email');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f9fc;
      text-align: center;
      margin-top: 100px;
    }
    form {
      background: white;
      display: inline-block;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    input {
      padding: 10px;
      margin: 10px 0;
      width: 200px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      padding: 10px 20px;
      background: #0078D4;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    button:hover { background: #005a9e; }
  </style>
</head>
<body>
  <h2>🔐 Verify OTP</h2>
  <p>An OTP has been sent to your email: <strong><?php echo htmlspecialchars($email); ?></strong></p>

  <form method="POST" action="../includes/auth.php">
      <input type="text" name="otp" placeholder="Enter 6-digit OTP" maxlength="6" required><br>
      <button type="submit" name="verify_otp">Verify OTP</button>
  </form>
</body>
</html>



<?php
// session_start();
// include_once '../includes/functions.php';

// // Prevent direct access without OTP session
// if (!isset($_SESSION['otp'])) {
//     echo "<script>alert('No OTP found. Please login or register first.'); window.location.href='login.php';</script>";
//     exit();
// }
?>
<!DOCTYPE html>
<!-- <html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f9fc;
            text-align: center;
            margin-top: 100px;
        }
        form {
            display: inline-block;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        input {
            padding: 10px;
            margin: 10px 0;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            padding: 10px 20px;
            background: #0078D4;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #005a9e;
        }
        .info {
            color: #555;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>🔐 Verify OTP</h2>
    <p class="info">
        <?php 
            echo "An OTP has been sent to your email <b>" . htmlspecialchars($_SESSION['email'] ?? 'unknown') . "</b>.<br>";
            echo "Please enter it below to continue.";
        ?>
    </p>
    
    <form method="POST" action="../includes/auth.php">
        <input type="text" name="otp" maxlength="6" placeholder="Enter 6-digit OTP" required><br>
        <button type="submit" name="verify_otp">Verify OTP</button>
    </form>
</body>
</html> -->
