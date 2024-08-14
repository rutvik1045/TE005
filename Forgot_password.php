<?php
// Start session
session_start();

// Database configuration
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

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => 'An unexpected error occurred.'];

    if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Prepare and execute query
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Email exists
                $response = ['success' => true];
            } else {
                // Email does not exist
                $response = ['success' => false, 'message' => 'Email address not found.'];
            }
            
            $stmt->close();
        } else {
            $response = ['success' => false, 'message' => 'Invalid email address'];
        }
    }

    echo json_encode($response);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .min-vh-100 {
            min-height: 100vh;
        }
        .invalid-feedback {
            display: none;
        }
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .was-validated .form-control:valid ~ .valid-feedback {
            display: block;
        }
    </style>
</head>
<body>

<section class="content min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Forgot Password</h3>
                    </div>
                    <form id="loginForm" class="needs-validation" novalidate method="POST" action="mail.php" style="display: none;">
                        <input type="hidden" name="email" id="hiddenEmail">
                    </form>
                    <form id="displayForm" class="needs-validation" novalidate method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="login_btn">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('#displayForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting the default way

        var email = $('#email').val();

        $.ajax({
            url: '', // The current PHP file
            type: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Email exists, submit the hidden form to mail.php
                    $('#hiddenEmail').val(email); // Set email value in hidden form
                    $('#loginForm').submit(); // Submit the hidden form
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.'
                });
            }
        });
    });
});
</script>

</body>
</html>
