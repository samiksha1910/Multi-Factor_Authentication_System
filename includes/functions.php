
<?php
include_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php'; // PHPMailer support

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ------------------ SECURITY HELPERS ------------------ */

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function isStrongPassword($password) {
    // Min 8 chars, at least 1 lowercase, 1 uppercase, 1 digit, 1 special char
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

/* ------------------ OTP GENERATOR ------------------ */
if (!function_exists('generateOTP')) {
    function generateOTP($length = 6) {
        // Create a 6-digit random number
        return strval(rand(pow(10, $length - 1), pow(10, $length) - 1));
    }
}

/* ------------------ EMAIL SENDER (PHPMailer) ------------------ */
function sendEmailOTP($to, $otp, $subject = "OTP Verification") {
    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        echo "<p style='color:red;'>❌ PHPMailer not found. Check composer autoload.</p>";
        return false;
    }

    try {
        $mail = new PHPMailer(true);

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'manjot.kaur0226@gmail.com';
        $mail->Password = 'zylkhfxikabbxwhv'; // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender & recipient
        $mail->setFrom('manjot.kaur0226@gmail.com', 'Secure Authentication System');
        $mail->addAddress($to);

        // Email body
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <h3>🔐 Email Verification</h3>
            <p>Your One-Time Password (OTP) is:</p>
            <h2 style='color:#0078D4;'>$otp</h2>
            <p>This OTP is valid for 10 minutes.</p>
        ";

        $mail->send();
        echo "<p style='color:green;'>✅ OTP sent successfully to {$to}</p>";
        return true;
    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ Mailer Error: {$mail->ErrorInfo}</p>";
        return false;
    }
}
?>
