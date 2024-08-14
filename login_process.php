<?php
session_start();
include('admin/config/dbcon.php');

if (isset($_POST['login_btn'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hash the entered password using MD5
    $hashed_password = md5($password);

    $log_query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $log_query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['password'] === $hashed_password) {
            // Store user data in session
            $_SESSION['auth'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['status'] = "Invalid credentials";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "Invalid credentials";
        header("Location: login.php");
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    $_SESSION['status'] = "Access Denied";
    header("Location: login.php");
    exit();
}
?>
