<?php
include_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!function_exists('log_action')) {
    function log_action($action, $details = '') {
        global $conn;

        if (!$conn || $conn->connect_errno) {
            error_log("❌ DB connection failed in log_action(): " . $conn->connect_error);
            return false;
        }

        $user_id = $_SESSION['user_id'] ?? 0;
        $action  = trim((string)$action);
        $details = trim((string)$details);

        $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, event, details, created_at) VALUES (?, ?, ?, NOW())");
        if (!$stmt) {
            error_log("❌ log_action prepare() failed: " . $conn->error);
            return false;
        }

        $stmt->bind_param("iss", $user_id, $action, $details);
        $stmt->execute();
        $stmt->close();

        return true;
    }
}
?>
<?php
include_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('log_action')) {
    function log_action($action, $details = '') {
        global $conn;

        if (!$conn || $conn->connect_errno) {
            error_log("❌ DB connection failed in log_action(): " . $conn->connect_error);
            return false;
        }

        $user_id = $_SESSION['user_id'] ?? 0;
        $action  = trim((string)$action);
        $details = trim((string)$details);

        $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, event, details, created_at) VALUES (?, ?, ?, NOW())");
        if (!$stmt) {
            error_log("❌ log_action prepare() failed: " . $conn->error);
            return false;
        }

        $stmt->bind_param("iss", $user_id, $action, $details);
        $stmt->execute();
        $stmt->close();

        return true;
    }
}
?>

<?php
function logAction($conn, $user_id, $action, $description) {
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, event, details, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $user_id, $action, $description);
    $stmt->execute();
    $stmt->close();
}
?>



