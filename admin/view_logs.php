<?php
include_once '../config/db.php';
include_once '../includes/log_action.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/access_denied.php');
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM audit_logs ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Audit Logs</title>
<style>
table { 
    border-collapse: collapse; 
    width: 100%; 
}
th, td { 
    padding: 8px; 
    border: 1px solid #ddd; 
    text-align: left; 
    }
</style>
</head>
<body>
<h2>Audit Logs</h2>
<?php if ($result && mysqli_num_rows($result) > 0): ?>
<table>
<tr><th>ID</th><th>User ID</th><th>Event</th><th>Details</th><th>When</th></tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['user_id'] ?></td>
<td><?= htmlspecialchars($row['event']) ?></td>
<td><?= htmlspecialchars($row['details']) ?></td>
<td><?= $row['created_at'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p>No logs found.</p>
<?php endif; ?>
<p><a href="manage_users.php">Back to Manage Users</a></p>
</body>
</html>
