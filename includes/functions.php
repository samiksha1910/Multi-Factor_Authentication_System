
<?php
include_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

if (!function_exists('generateOTP')) {
    function generateOTP($length = 6) {
        return strval(rand(pow(10, $length - 1), pow(10, $length) - 1));
    }
}

function sendEmailOTP($to, $otp, $subject = "OTP Verification") {
    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        echo "<p style='color:red;'>❌ PHPMailer not found. Check composer autoload.</p>";
        return false;
    }

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'manjot.kaur0226@gmail.com';
        $mail->Password = 'zylkhfxikabbxwhv'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('manjot.kaur0226@gmail.com', 'Secure Authentication System');
        $mail->addAddress($to);

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
