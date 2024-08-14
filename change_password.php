<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
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

    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Get form data
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current password from the database
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_password_hash);
    $stmt->fetch();
    $stmt->close();

    // Verify old password
    if (md5($old_password) === $current_password_hash) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            // Hash the new password with MD5
            $new_password_hash = md5($new_password);

            // Update the password in the database
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password_hash, $user_id);

            if ($stmt->execute()) {
                echo "Password changed successfully.";
            } else {
                echo "Error updating password: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo "New password and confirm password do not match.";
        }
    } else {
        echo "Old password is incorrect.";
    }

    // Close the connection
    $conn->close();
}
?>
