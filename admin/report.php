<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);
$reason = trim($data['reason'] ?? '');

if ($reason === '') {
    echo json_encode(['success' => false, 'message' => 'Reason is required.']);
    exit;
}

include_once __DIR__ . '/../config/db.php'; 

$user_id = (int) $_SESSION['user_id'];
$email = $_SESSION['email'];

$reason_clean = strip_tags($reason);

$sql = "INSERT INTO account_reports (user_id, email, reason) VALUES (?, ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('iss', $user_id, $email, $reason_clean);
    if ($stmt->execute()) {
        $report_id = $stmt->insert_id;
        $adminEmail = 'admin@example.com'; 
        $subject = "New Account Compromised Report (#$report_id)";
        $body = "User ID: $user_id\nEmail: $email\n\nReason:\n$reason_clean\n\nView in admin panel.";
        

        echo json_encode(['success' => true, 'message' => 'Report submitted successfully.']);
    } else {
        error_log("Report insert failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
    $stmt->close();
} else {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
.btn {
  font-weight: 600;
  padding: 10px 18px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-size: 15px;
}
.btn-danger { background: #e83e56; color: #fff; }
.btn-primary-alt { background: #6b46ff; color: #fff; }
.btn-outline { background: transparent; border: 1px solid #ccc; color:#333; }

.modal-overlay {
  position: fixed;
  inset: 0;
  display: none; 
  align-items: center;
  justify-content: center;
  background: rgba(10, 12, 15, 0.4);
  z-index: 9999;
  padding: 20px;
}
.modal-overlay[aria-hidden="false"] { display: flex; }

.modal-card {
  width: 100%;
  max-width: 720px;
  background: #fff;
  padding: 28px;
  border-radius: 12px;
  box-shadow: 0 6px 30px rgba(20,30,40,0.08);
}
.modal-card h2 { margin: 0 0 10px; }
.modal-card textarea {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #e6eef6;
  resize: vertical;
}
.modal-actions {
  display:flex;
  gap:12px;
  justify-content:flex-end;
  margin-top:12px;
}
#reportMsg { margin-top:12px; padding:10px; border-radius:6px; background:#f4f7fb; }

    </style>
</head>
<body>
<div class="report-actions">
  <button id="reportBtn" class="btn btn-danger">Report Account Compromised</button>
  <a href="contact_admin.php" class="btn btn-primary-alt">Contact Admin</a>
</div>

<div id="reportModal" class="modal-overlay" aria-hidden="true">
  <div class="modal-card">
    <h2>Report Account Compromised</h2>
    <p>Please provide a short reason so admin can review and act.</p>
    <form id="reportForm">
      <label for="reason">Reason</label>
      <textarea id="reason" name="reason" rows="5" required placeholder="Describe what happened..."></textarea>
      <div class="modal-actions">
        <button type="button" id="cancelBtn" class="btn btn-outline">Cancel</button>
        <button type="submit" class="btn btn-danger">Report Account Compromised</button>
      </div>
    </form>
    <div id="reportMsg" role="status" style="display:none"></div>
  </div>
</div>
<script>

document.addEventListener('DOMContentLoaded', () => {
  const reportBtn = document.getElementById('reportBtn');
  const modal = document.getElementById('reportModal');
  const cancelBtn = document.getElementById('cancelBtn');
  const reportForm = document.getElementById('reportForm');
  const reportMsg = document.getElementById('reportMsg');

  function openModal() {
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }
  function closeModal() {
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    reportForm.reset();
    reportMsg.style.display = 'none';
  }

  reportBtn.addEventListener('click', openModal);
  cancelBtn.addEventListener('click', closeModal);

  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  reportForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    reportMsg.style.display = 'none';

    const reason = document.getElementById('reason').value.trim();
    if (!reason) {
      reportMsg.textContent = 'Please provide a reason.';
      reportMsg.style.display = 'block';
      return;
    }

    // send to server
    try {
      const resp = await fetch('/secure_auth_project/public/report.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ reason })
      });
      const data = await resp.json();
      if (data.success) {
        reportMsg.textContent = data.message || 'Report submitted. Admin will review.';
        reportMsg.style.display = 'block';
        reportMsg.style.background = '#e6ffef';
        // optionally close after short delay
        setTimeout(closeModal, 1800);
      } else {
        reportMsg.textContent = data.message || 'Unable to submit report. Try again.';
        reportMsg.style.display = 'block';
      }
    } catch (err) {
      reportMsg.textContent = 'Network error. Try again.';
      reportMsg.style.display = 'block';
      console.error(err);
    }
  });
});
</script>

</body>
</html>