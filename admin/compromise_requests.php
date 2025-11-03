<?php
include_once '../config/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/access_denied.php');
    exit();
}

$result = $conn->query("SELECT id, user_id, report_reason AS description, status, created_at FROM compromise_reports");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Compromise Reports</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; margin: 20px; }
        h2 { text-align: center; color: #333; }
        table { width: 90%; margin: auto; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: center; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:hover { background: #f1f1f1; }
        .status { font-weight: bold; padding: 5px 10px; border-radius: 5px; }
        .Pending { color: #856404; background-color: #fff3cd; }
        .Reviewed { color: #0c5460; background-color: #d1ecf1; }
        .Resolved { color: #155724; background-color: #d4edda; }
        a.btn { text-decoration: none; color: white; background: #28a745; padding: 6px 12px; border-radius: 5px; }
        a.btn:hover { background: #218838; }
    </style>
</head>
<body>
    <h2>Compromise Reports</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['user_id']; ?></td>
            <td><?= htmlspecialchars($row['description']); ?></td>
            <td><span class="status <?= $row['status']; ?>"><?= $row['status']; ?></span></td>
            <td><?= $row['created_at']; ?></td>
            <td><a class="btn" href="update_report.php?id=<?= $row['id']; ?>">Update</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
