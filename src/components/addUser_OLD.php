<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

include('../database/pgconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Generate a random 6-digit verification code with a space in the middle
    $firstThreeDigits = mt_rand(100, 999);
    $lastThreeDigits = mt_rand(100, 999);
    $verificationCode = $firstThreeDigits . ' ' . $lastThreeDigits;

    // Save the verification code in the session
    session_start();
    $_SESSION['verification_code'] = $verificationCode;

    // Insert the user with verification code into the database
    $query = "INSERT INTO users (username, email, password, verification_token, verified) VALUES (:username, :email, :password, :verification_token, false)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':verification_token', $verificationCode);

    // Send the verification code via email
    $to = $email;
    $subject = 'Email Verification Code';
    $message = "Your verification code is: $verificationCode";

    // Use PHPMailer to send the email
    $mail = new PHPMailer();

    // Set up the SMTP server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Update with your SMTP host
    $mail->SMTPAuth = true;
    $mail->Username = 'monitoringactivity6@gmail.com'; // Update with your SMTP username
    $mail->Password = 'txjy cfen ljga cqdp'; // Update with your SMTP password
    $mail->SMTPSecure = 'ssl'; // Update with your SMTP encryption (tls or ssl)
    $mail->Port = 465; // Update with your SMTP port

    // Set the 'From' address
    $mail->setFrom('monitoringactivity6@gmail.com'); // Update with your email and name

    // Set the 'To' address
    $mail->addAddress($to);

    // Set email subject and body
    $mail->Subject = $subject;
    $mail->Body = $message;

    // Send the email
    if ($mail->send()) {
        // Email sent successfully
        echo "Email sent successfully! Please check your email for the verification code.";
    } else {
        // Email sending failed
        echo "Error sending verification email: " . $mail->ErrorInfo;
    }
}

// Close PDO Connection
if ($conn instanceof PDO) {
    $conn = null; // Close PDO connection
}
