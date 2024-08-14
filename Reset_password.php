<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "te005";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $conn->prepare('SELECT email FROM password_resets WHERE token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();
    } else {
        echo "Invalid or expired token.";
        exit();
    }
} else {
    echo "No token provided.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($newPassword) < 8 || 
        !preg_match('/[A-Z]/', $newPassword) || 
        !preg_match('/[a-z]/', $newPassword) || 
        !preg_match('/[0-9]/', $newPassword) || 
        !preg_match('/[\W_]/', $newPassword)) {
        echo "Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    $hashedPassword = md5($newPassword);

    $stmt = $conn->prepare('UPDATE users SET password = ? WHERE email = ?');
    $stmt->bind_param('ss', $hashedPassword, $email);
    $stmt->execute();

    $stmt = $conn->prepare('DELETE FROM password_resets WHERE token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();

    $_SESSION['status'] = 'Password has been successfully reset.';
    header('Location: login.php');
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-message {
            color: red;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Reset Your Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" onsubmit="return validatePassword()" class="mt-4">
                            <div class="form-group mb-3">
                                <label for="new_password" class="form-label">New Password:</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <div id="new_password_error" class="error-message"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                <div id="confirm_password_error" class="error-message"></div>
                            </div>
                            <div class="text-left">
                                <button type="submit" class="btn btn-primary font-weight-bold btn-sm p-24">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validatePassword() {
            const newPassword = document.getElementById('new_password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();
            let isValid = true;

            document.getElementById('new_password_error').innerText = '';
            document.getElementById('confirm_password_error').innerText = '';

            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!passwordPattern.test(newPassword)) {
                document.getElementById('new_password_error').innerText = 'Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.';
                isValid = false;
            }

            if (newPassword !== '' && confirmPassword !== '' && newPassword !== confirmPassword) {
                document.getElementById('confirm_password_error').innerText = 'Passwords do not match.';
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>
</html>
