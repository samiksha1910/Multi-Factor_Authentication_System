
<?php
session_start();
include_once '../config/db.php';      // must set $conn (mysqli)
include_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    die('Missing id');
}

$id = intval($_GET['id']); // sanitize as integer

// Make sure column name is correct: 'report_status' or 'status'
$column = 'report_status'; // change if your column is named 'status'

$stmt = $conn->prepare("UPDATE compromise_reports SET {$column} = ? WHERE id = ?");
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$status = 'approved';
$stmt->bind_param('si', $status, $id); // s = string, i = integer

if (!$stmt->execute()) {
    // Useful for debugging
    die('Execute failed: ' . $stmt->error);
}

if ($stmt->affected_rows > 0) {
    // success
    header('Location: compromise_requests.php?msg=approved');
    exit();
} else {
    // nothing changed (maybe already approved or id not found)
    header('Location: compromise_requests.php?msg=nochange');
    exit();
}
?>