<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "te005";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$contact_number = $_POST['contact_number'];

$sql = "UPDATE users SET first_name=?, last_name=?, email=?, contact_number=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $first_name, $last_name, $email, $contact_number, $id);

if ($stmt->execute()) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
