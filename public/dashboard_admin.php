<?php
include_once '../includes/session_check.php';
include_once '../includes/functions.php';

// ✅ Only allow admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: access_denied.php?reason=Admin%20Area');
    exit();
}

// ✅ Determine name to show safely
$displayName = $_SESSION['username'] 
    ?? ($_SESSION['first_name'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .card {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background: #f0f4f8;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
        .logout {
            display: inline-block;
            margin-top: 25px;
            background: #e63946;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
        }
        .logout:hover {
            background: #c72d38;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($displayName); ?> (Admin)</h1>

    <div class="card">
        <h3>Admin Controls</h3>
        <ul>
            <li><a href="../admin/manage_users.php">Manage Users</a></li>
            <li><a href="../admin/system_logs.php">System Logs</a></li>
            <li><a href="../admin/compromise_requests.php">Report Compromise</a></li>
        </ul>
    </div>

    <a href="logout.php" class="logout">Logout</a>
</div>

<script src="../assets/js/popup.js"></script>
</body>
</html>
