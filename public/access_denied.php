<?php include_once '../includes/functions.php'; 
$reason = isset($_GET['reason']) ? sanitize($_GET['reason']) : 'Restricted by system policy'; ?>
<!DOCTYPE html><html><head><meta charset='utf-8'><title>Access Denied</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="popup">
    <h2>🚫 Access Blocked</h2>
    <p>Your system has restricted this page.</p>
    <p><b>Reason:</b> <?php echo $reason; ?></p>
</div>
</body></html>