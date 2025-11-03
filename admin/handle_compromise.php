<?php
include_once '../config/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/access_denied.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];


    
    // Determine the new status
    if ($action === 'approve') {
    $newStatus = 'action_taken';
} elseif ($action === 'reject') {
    $newStatus = 'dismissed';
}
 else {
        header("Location: compromise_requests.php?error=Invalid action");
        exit();
    }

    // Prepare update query
    $stmt = $conn->prepare("UPDATE compromise_reports SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $id);

    if ($stmt->execute()) {
        header("Location: compromise_requests.php?msg=success");
    } else {
        header("Location: compromise_requests.php?error=Database update failed");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: compromise_requests.php?error=Missing parameters");
    exit();
}
?>
