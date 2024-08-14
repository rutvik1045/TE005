<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
                        <h3 class="card-title">Login</h3>
                    </div>
                    <form id="loginForm" class="needs-validation" novalidate method="POST" action="login_process.php">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="8">
                                <div class="invalid-feedback">
                                    Please enter your password (at least 8 characters).
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="login_btn">Login</button>
                                <div class="link pt-3">  
                                    <p class="mb-0 m-0">Don't have an account? 
                                        <a href="Registation.php" class="btn btn-link mb-0 m-0 p-0">Create an account</a>
                                    </p>
                                    <a href="Forgot_password.php" class="btn btn-link p-0">Forgot password</a>
                                </div>
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
        $('#loginForm').on('submit', function(event) {
            $(this).removeClass('was-validated');
            if (!this.checkValidity()) {
                event.preventDefault();
                $(this).addClass('was-validated');
                return;
            }
        });

        // Display SweetAlert based on session status
        <?php if (isset($_SESSION['status'])): ?>
            let status = "<?php echo $_SESSION['status']; ?>";
            <?php unset($_SESSION['status']); ?>
            Swal.fire({
                position: 'top-end',
                icon: 'info', // Default icon, can be changed based on the message type
                title: status,
                showConfirmButton: false,
                timer: 5000, // Duration in milliseconds
                toast: true
            });
        <?php endif; ?>
    });
</script>

</body>
</html>
