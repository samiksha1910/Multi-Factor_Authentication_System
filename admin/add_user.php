<?php
include_once '../config/db.php';
include_once '../includes/log_action.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/access_denied.php");
    exit();
}

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = in_array($_POST['role'], ['admin','user']) ? $_POST['role'] : 'user';

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
         log_action('Add User', "Admin added user: $email");
        echo "<script>alert('User added successfully!');window.location='manage_users.php';</script>";
        exit();
    } else {
        echo "Error adding user: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add User</title>

<style>
     body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #dbeeff, #eaf5ff);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    h2 {
    text-align: center;
    color: #1e3a8a;
    font-size: 40px;
    margin-bottom: 25px;
    transition: color 0.3s ease;
    position: absolute;
    top: -10px;
    right: 560px;
}

    form {
        height: 520px;
        width: 500px;
        border-radius: 30px 0px;
        align-content: center;
        justify-items: center;
        justify-self: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    label {
        margin-left: 200px !important;
        width: 100%;
        font-size: 16px;
        font-weight: 600;
        color: #374151;
        display: block;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2px;
    }

    select {
        width: 290px !important;
    }


    input,select{
        margin-left: 100px !important;
        padding: 7px 15px;
        margin: 5px;
        font-size: 16px;
        border-radius: 5px;
        width: 250px;
        border-style: hidden;
        box-shadow: 0px 0px 10px 0px #d1d5db;
        color: #858484 !important;
        border: 2px solid #d1d5db;
        transition: all 0.3s ease;
    }

    input:focus {
        border-color: #2563eb;
        outline: none;
    }

    button {
        margin-top: 30px;
        margin-left: 120px !important;
        width: 50%;
        background-color: #2563eb;
        color: white;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        padding: 12px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
        background-color: #1d4ed8;
        transform: translateY(-2px);
    }

    p {
    text-align: center;
    color: #1a2d57;
    font-size: 18px;
}

    a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
    position: relative;
    bottom: -230px;
    right: 300px;
}

    a:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>
<h2>Add New User</h2>
<form method="post">
    <label>
        Username: 
    </label>
        <input type="text" name="username" required><br><br>
    <label>
        Email: 
    </label>
        <input type="email" name="email" required><br><br>
    <label>
        Password: 
    </label>
        <input type="password" name="password" required><br><br>
    <label>
        Role:
    </label>
    <select name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select><br><br>
    <button type="submit" name="add_user">Add User</button>
</form>
<p><a href="manage_users.php">Back to list</a></p>
</body>
</html>
