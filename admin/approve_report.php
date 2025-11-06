
<?php
session_start();
include_once '../config/db.php';      
include_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    die('Missing id');
}

$id = intval($_GET['id']); 

$column = 'report_status'; 

$stmt = $conn->prepare("UPDATE compromise_reports SET {$column} = ? WHERE id = ?");
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$status = 'approved';
$stmt->bind_param('si', $status, $id); 

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

if ($stmt->affected_rows > 0) {
    header('Location: compromise_requests.php?msg=approved');
    exit();
} else {
    header('Location: compromise_requests.php?msg=nochange');
    exit();
}
?>