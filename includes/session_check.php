<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, username FROM users WHERE id=?");


$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    $blocked = $user['blocked'] ?? 0;
    $blocked_until = $user['blocked_until'] ?? null;
    $role = $user['role'] ?? 'user';

    if ($blocked == 1) {
        echo "<script>alert('Your account is temporarily blocked.');window.location='../public/login.php';</script>";
        session_destroy();
        exit();
    }

} else {
    session_destroy();
    header("Location: ../public/login.php");
    exit();
}
?>
