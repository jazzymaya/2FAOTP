<?php
//Use this code if your OTP email submission code is not working. Code from https://github.com/PHPMailer/PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';


$mail = new PHPMailer(true);
try {
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->IsSMTP();
$mail->SMTPAuth   = TRUE;
//Change this if you are not use TLS
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//Change this if you are not use TLS
$mail->Port       = 587;
//Enter your mail server's address. Example, gmail is smtp.gmail.com
$mail->Host       = "";
//Enter your email address
$mail->Username   = "";
//Enter your email password
$mail->Password   = "";

$mail->IsHTML(true);
$mail->AddAddress("receipient-email@email.com", "ReceipientName");
$mail->SetFrom("your-email@email.com", "YourName");
//$mail->AddReplyTo("reply-to-email", "reply-to-name");
//$mail->AddCC("cc-recipient-email", "cc-recipient-name");
$mail->Subject = "Test is Test Email sent via ... SMTP Server using PHP Mailer";
$mail->Body = "<b>This is a Test Email sent via ... SMTP Server using PHP mailer class.</b>";

$mail->send(); 
echo "Message has been sent";
} catch (Exception $e) {
echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
