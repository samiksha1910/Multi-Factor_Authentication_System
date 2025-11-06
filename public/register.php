<?php 
include_once '../includes/functions.php';
include('../config/db.php'); 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Register</title>

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #dbeafe, #eff6ff);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin: 0;
    }

    .register-container {
        background: #fff;
        padding: 40px 50px;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        width: 380px;
        opacity: 0;
        transform: translateY(40px);
        animation: fadeInUp 1s ease-out forwards;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(40px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

   h2 {
    text-align: center;
    color: #1e3a8a;
    font-size: 40px;
    margin-bottom: 25px;
    transition: color 0.3s ease;
    position: absolute;
    top: 0;
    right: 570px;
}

    h2:hover {
        color: #2563eb;
    }



    label {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2px;
    }


    input,select{
        padding: 7px 15px;
  margin: 5px;
  font-size: 16px;
  border-radius: 5px;
  width: 300px;
  border-style: hidden;
  box-shadow: 0px 0px 10px 0px #d1d5db;
  color: #858484 !important;
  border: 2px solid #d1d5db;
  transition: all 0.3s ease;
    }

    select{
        width: 335px !important;
    }

    input:focus, select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 10px rgba(59,130,246,0.2);
        transform: scale(1.02);
        outline: none;
    }

    button {
        width: 100%;
        background: #2563eb;
        border: none;
        padding: 12px;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.4s ease;
        box-shadow: 0 4px 10px rgba(37,99,235,0.3);
    }

    button:hover {
        background: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(37,99,235,0.4);
    }

    .login-link {
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
    }

    .login-link a {
        color: #2563eb;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .login-link a:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }
p{
    position: absolute;
    bottom: 20px;
}
.form-style{
    /* background-color: #dbf1feff; */
  height: 525px;
  width: 500px;
  border-radius: 30px 0px;
  align-content: center;
  justify-items: center;
  justify-self: center;
  margin: 40px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1)
}

p {
    text-align: center;
    font-size: 18px;
}
   a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
}

    a:hover {
        text-decoration: underline;
    }



</style>
</head>

<body>
<h2>Register</h2>
<div class="form-style">
<form method="POST" action="../includes/auth.php" onsubmit="return validatePassword();">
    <label>Username:</label> 
    <input type="text" name="username" required><br>
    

    <label>Email:</label> 
    <input type="email" name="email" required><br>

    <label>Password:</label> 
    <input type="password" id="password" name="password" required><br>

    <label>Confirm Password:</label> 
    <input type="password" id="confirm_password" required><br>

    <label>Role:</label>
    <select name="role">
        <option value="user" selected>User</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <button type="submit" name="register">Register</button>
</form>

</div>

<p>Already registered? <a href="login.php">Login</a></p>

<script>
function validatePassword() {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!pattern.test(password)) {
        alert('Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.');
        return false;
    }

    if (password !== confirm) {
        alert('Passwords do not match!');
        return false;
    }

    return true;
}
</script>
</body>
</html>
