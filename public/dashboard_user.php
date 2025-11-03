<?php 
include_once '../includes/session_check.php'; 
include_once '../includes/functions.php'; 

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'user') {
    header('Location: access_denied.php?reason=User%20Only%20Page'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>User Dashboard | Secure Auth</title>
<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: #f7f8fc;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 15px auto;
        background: #eaf5ff;
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        padding: 40px 30px;
        text-align: center;
    }

    h1 {
        color: #111827;
        font-size: 28px;
        margin-bottom: 40px;
    }

    .card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        padding: 20px;
        margin: 20px auto;
        width: 80%;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
    }

    h3 {
        color: #1e3a8a;
        font-size: 20px;
        margin-bottom: 10px;
    }

    ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    ul li {
        margin: 8px 0;
    }

    a {
        text-decoration: none;
        color: #2563eb;
        font-weight: 500;
        transition: 0.3s;
    }

    a:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    button {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 15px;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    button:hover {
        background: #2563eb;
        transform: scale(1.05);
    }

    .logout-link {
        margin-top: 30px;
    }

    .logout-link a {
        color: #ef4444;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }

    .logout-link a:hover {
        color: #dc2626;
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            width: 90%;
            padding: 25px 20px;
        }
        .card {
            width: 95%;
        }
        h1 {
            font-size: 22px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (User)</h1>

    <div class="card">
        <h3>Help & Support</h3>
        <ul>
            <li><a href="helper.php">Security Policies & Compromise Reporting Guide</a></li>
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
    fetch('../includes/request_access.php', {
        method:'POST', 
        headers:{'Content-Type':'application/x-www-form-urlencoded'}, 
        body:'page='+encodeURIComponent(page)
    })
    .then(r=>r.text()).then(t=>alert(t));
    return false;
}
</script>
</body>
</html>
