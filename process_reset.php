<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "te005";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['token'], $_POST['email'], $_POST['password'])) {
    // Sanitize input data
    $token = trim($_POST['token']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    if ($email === false) {
        $_SESSION['status'] = 'Invalid email address.';
        header('Location: login.php');
        exit();
    }

    // Validate the token
    $stmt = $pdo->prepare('SELECT id FROM password_resets WHERE token = :token AND email = :email AND created_at > NOW() - INTERVAL 1 HOUR');
    $stmt->execute(['token' => $token, 'email' => $email]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        // Update the user’s password (consider hashing if security becomes a priority)
        $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE email = :email');
        $stmt->execute(['password' => $password, 'email' => $email]);

        // Remove the token
        $stmt = $pdo->prepare('DELETE FROM password_resets WHERE token = :token');
        $stmt->execute(['token' => $token]);

        $_SESSION['status'] = 'Password has been reset successfully.';
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['status'] = 'Invalid or expired token.';
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION['status'] = 'Incomplete request.';
    header('Location: login.php');
    exit();
}
?>