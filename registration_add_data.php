<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "te005";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
$lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
$contactNumber = filter_input(INPUT_POST, 'contactNumber', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid email address.');
}

// Check if email already exists
$emailCheckStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
if ($emailCheckStmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$emailCheckStmt->bind_param("s", $email);
$emailCheckStmt->execute();
$emailCheckStmt->bind_result($emailCount);
$emailCheckStmt->fetch();
$emailCheckStmt->close();

if ($emailCount > 0) {
    die('Email already registered.');
}

// Hash the password using MD5
$hashedPassword = md5($password);

$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, contact_number, email, password) VALUES (?, ?, ?, ?, ?)");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("sssss", $firstName, $lastName, $contactNumber, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "An error occurred: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
?>
