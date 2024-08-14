<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "te005";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rutvikkher11@gmail.com';
    $mail->Password   = 'ufbq iwnk dqwj ckdy'; // Replace with your SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('rutvikkher11@gmail.com', 'Rutvik');
    $mail->addReplyTo('rutvikkher11@gmail.com', 'Rutvik');

    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $userEmail = $_POST['email'];
        $token = bin2hex(random_bytes(16)); // Generate a secure token

        // Store token and email in the database
        $stmt = $conn->prepare('INSERT INTO password_resets (email, token) VALUES (?, ?)');
        $stmt->bind_param('ss', $userEmail, $token);
        $stmt->execute();

        $mail->addAddress($userEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';

        // Properly URL-encode the token parameter
        $resetLink = 'http://localhost/TE005/Reset_password.php?token=' . urlencode($token);
        $mail->Body    = 'Click here to <a href="' . $resetLink . '">reset your password</a>.';
        $mail->AltBody = 'Click here to reset your password: ' . $resetLink;

        $mail->send();
        $_SESSION['status'] = 'Password reset email has been sent successfully.';
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['status'] = 'Invalid email address.';
        header('Location: login.php');
        exit();
    }
} catch (Exception $e) {
    $_SESSION['status'] = 'There was an error sending the email. Error: ' . $mail->ErrorInfo;
    header('Location: login.php');
    exit();
}

$conn->close();
?>
