<?php include_once '../includes/session_check.php'; include_once '../includes/functions.php'; 
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'user') {
    header('Location: access_denied.php?reason=User%20Only%20Page'); exit();
}
?>
<!DOCTYPE html><html><head><meta charset='utf-8'><title>User Dashboard</title><link rel="stylesheet" href="../assets/css/style.css">

<style>

  body{
    background-color: white;
  }

div{
  background-color: #eaf5ff;
  border-radius: 30px 0px;
  align-content: center;
  justify-items: center;
  justify-self: center;
  margin: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}


</style>

</head><body>
<div class="container">
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (User)</h1>
  <div class="card">
    <h3>Your links</h3>
    <ul>
      <li><a href="#" onclick="showDenied('Admin permissions required')">Admin Panel (Restricted)</a></li>
      <li><a href="helper.php">Help & Support</a></li>
      <li>
        <form method="POST" action="../includes/request_access.php" onsubmit="return requestAccess(event, 'admin_panel.php')">
          <input type="hidden" name="page" value="admin_panel.php">
          <button type="submit">Request Access</button>
        </form>
      </li>
    </ul>
  </div>
  <div class="card">
    <h3>Security</h3>
    <form method="POST" action="../includes/report_compromise.php">
      <input type="hidden" name="reason" value="I forgot to logout and someone used my account">
      <button type="submit">Report Account Compromised</button>
    </form>
  </div>
 <div class="logout-link">
   <a href="logout.php">Logout</a>
</div>
</div>
<script src="../assets/js/popup.js"></script>
<script>
function requestAccess(e, page) {
    e.preventDefault();
    fetch('../includes/request_access.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'page='+encodeURIComponent(page)})
    .then(r=>r.text()).then(t=>alert(t));
    return false;
}
</script>
</body></html>