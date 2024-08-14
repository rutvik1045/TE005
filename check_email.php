<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "te005";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo 'unavailable'; // Email exists
    } else {
        echo 'available'; // Email is available
    }
}

$conn->close();
?>
