

<?php
session_start();
include_once '../config/db.php';
include_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Login required!'); window.location.href='../pages/login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$reason = isset($_POST['reason']) ? sanitize($_POST['reason']) : 'User reported compromise';

$stmt = $conn->prepare("INSERT INTO compromise_reports (user_id, report_reason) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $reason);
$stmt->execute();
$report_id = $stmt->insert_id;
$stmt->close();

$ev = 'compromise_reported';
$details = 'report_id:' . $report_id . ', user_id:' . $user_id;
$stmt2 = $conn->prepare("INSERT INTO audit_logs (user_id, event, details) VALUES (?, ?, ?)");
$stmt2->bind_param("iss", $user_id, $ev, $details);
$stmt2->execute();
$stmt2->close();

$admin_email = 'kourmanjot13@gmail.com';
$subject = "Compromise report #$report_id";
$message = "User ID: $user_id reported account compromise.\nReason: $reason";
@mail($admin_email, $subject, $message);

echo "<script>alert('Report submitted successfully! Admin has been notified.'); window.location.href='../public/dashboard_user.php';</script>";
exit();
?>
