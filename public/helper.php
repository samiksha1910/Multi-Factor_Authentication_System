
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Help & Documentation | Secure Auth</title>
  <link rel="stylesheet" href="../assets/css/help.css">
  <style>
:root{
  --bg:#f6fbff;
  --card:#ffffff;
  --muted:#6b7280;
  --accent:#4f46e5;
  --danger:#e11d48;
  --glass: rgba(255,255,255,0.85);
  --radius:12px;
  --shadow: 0 6px 18px rgba(15,23,42,0.08);
  --container: 980px;
}

*{
  box-sizing:border-box;
  font-family:Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}
body{
  margin:0;background:var(--bg);
  color:#111;
  line-height:1.5;
}
.container{
  max-width:var(--container);
  margin:0 auto;
  padding:28px;
}

.site-header{
  background:linear-gradient(90deg, #fff 0%, #fbfdff 100%);
  border-bottom:1px solid rgba(0,0,0,0.04);
}
.site-header .container{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:18px 28px;
}
.brand{
  font-size:20px;margin:0;
  color:var(--accent);
  font-weight:700;
}
 
  
.nav a{
  margin-left:14px;color:#374151;
  text-decoration:none;
  font-weight:600;
}
.nav a.active{
  color:var(--accent);
}

.main-grid{
  display:grid;
  grid-template-columns:1fr 320px;
  gap:22px;padding:30px 0;
}

@media (max-width:900px)
{ .main-grid
  {
    grid-template-columns:1fr;
   padding:18px 16px;
  } 
}

.card
{
   background:var(--card);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  padding:18px;
}
.card.wide
{
  grid-column:1/-1;
}
.card h2
{
  margin:0 0 8px 0;
  font-size:20px;
  color:#0f172a;
}
.muted
{
  color:var(--muted);
  margin-bottom:12px;
  font-size:14px;
}

.policy-list
{
  margin:12px 0 0 18px;
  padding:0;
}
.policy-list li
{
  margin:8px 0;
  color:#0f172a;
}

.steps
{
  margin:12px 0 0 18px;
  padding:0;
}
.steps li
{
  margin:10px 0;
  color:#0f172a;
}

.cta-row
{
  display:flex;
  gap:10px;
  margin-top:14px;
}
.btn{
display:inline-block;
background:var(--accent);
color:#fff;
padding:10px 14px;
border-radius:8px;
text-decoration:none;
border:none;
font-weight:700;
cursor:pointer;
}
.btn.danger{
background:var(--danger);
}

.rules{
margin:12px 0 0 18px;
padding:0;
}
.rules li{
margin:8px 0;
color:#0f172a;
}
.password-demo{
margin-top:10px;
padding:10px;
background:#f1f5f9;
border-radius:8px;
font-family:monospace;
color:#0f172a;
}

.faq details{
margin:10px 0;
padding:10px;
border-radius:8px;
background:#fbfdff;
border:1px solid rgba(15,23,42,0.03);
}
.faq summary{
cursor:pointer;
font-weight:600;
}

.site-footer{
padding:20px 0;
text-align:center;
color:var(--muted);
font-size:14px;
border-top:1px solid rgba(0,0,0,0.04);
background:transparent;
}

  </style>
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1 class="brand">SecureAuth — Help & Documentation</h1>
      <nav class="nav">
        <a href="dashboard_user.php">Dashboard</a>
        <a href="helper.php" class="active">Help</a>
        <a href="logout.php">Logout</a>
      </nav>
    </div>
  </header>

  <main class="container main-grid">
    <section class="card wide">
      <h2>System Access Policies</h2>
      <p class="muted">
        These policies describe what the system allows and what requires administrative approval.
        They are enforced to protect user accounts and system integrity.
      </p>

      <ul class="policy-list">
        <li><b>Role-based access:</b> Admins have elevated privileges; regular users have limited access.</li>
        <li><b>Session security:</b> Sessions expire after inactivity. Always logout on shared computers.</li>
        <li><b>Blocked actions:</b> Any attempt to access admin-only pages will be blocked and logged.</li>
        <li><b>Account compromise:</b> If you suspect your credentials are leaked, report immediately — admin will block the account.</li>
      </ul>
    </section>

    <section class="card">
      <h2>How to report a compromised account</h2>
      <p class="muted">Follow these steps immediately if you think someone else used your account:</p>

      <ol class="steps">
        <li>Open your <b>Dashboard → Security</b> and click <b>"Report Account Compromised"</b>.</li>
        <li>Provide a short reason (e.g. "I forgot to logout and someone used my account").</li>
        <li>Admin will review the request and may block your account to protect it.</li>
        <li>Once blocked, you will receive an email with instructions to <b>reset your password</b> and recover access.</li>
        <li>After reset, admin will re-enable your account if everything is secure.</li>
      </ol>

      <div class="cta-row">
        <form method="POST" action="../includes/report_compromise.php">
          <input type="hidden" name="reason" value="User reported compromise via Help page">
          <button class="btn danger" type="submit">Report Account Compromised</button>
        </form>
      </div>
    </section>

    <section class="card">
      <h2>Password Guidelines</h2>
      <p class="muted">Follow these rules to keep your account safe. These are enforced on registration and password-reset.</p>
      <ul class="rules">
        <li>Minimum <b>8 characters</b></li>
        <li>At least <b>1 uppercase</b> letter (A–Z)</li>
        <li>At least <b>1 lowercase</b> letter (a–z)</li>
        <li>At least <b>1 number</b> (0–9)</li>
        <li>At least <b>1 special</b> character (e.g. <code>!@#$%</code>)</li>
        <li>Do <b>not reuse</b> passwords you use elsewhere</li>
      </ul>
      <div class="password-demo">
        <p><b>Example strong password:</b> <code>MyS3cure!2025</code></p>
      </div>
    </section>

    <section class="card wide">
      <h2>FAQ & Troubleshooting</h2>
      <div class="faq">
        <details>
          <summary>What happens when my account is blocked?</summary>
          <p>The account is temporarily disabled. You cannot access any protected pages. Admin will instruct you to reset your password before restoring access.</p>
        </details>

        <details>
          <summary>How long do OTPs remain valid?</summary>
          <p>OTPs are valid for <b>10 minutes</b>. If an OTP expires, request a new one during registration or password reset.</p>
        </details>

        <details>
          <summary>I didn't receive the OTP email — what do I do?</summary>
          <p>First check your spam folder. If it’s not there, contact admin or try resending the OTP from the registration/reset flow.</p>
        </details>

        <details>
          <summary>Can admin unlock my account remotely?</summary>
          <p>Yes — admin can unblock an account after verification. Admin will usually require you to reset your password first.</p>
        </details>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>© SecureAuth — Demo project. For educational use only.</p>
    </div>
  </footer>
</body>
</html>