<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
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
                        <h3 class="card-title">Registration Form</h3>
                    </div>
                    <form id="registrationForm" class="needs-validation" novalidate>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
                                <div class="invalid-feedback">Please enter your first name.</div>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                                <div class="invalid-feedback">Please enter your last name.</div>
                            </div>
                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="tel" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number" required pattern="[0-9]{10}">
                                <div class="invalid-feedback">Please enter a valid contact number (10 digits).</div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}">
                                <div class="invalid-feedback">
                                    Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                                <div class="invalid-feedback">Passwords do not match.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <br><br>
                            <p class="p-0 m-0">Already have an account? <a href="login.php" class="btn btn-link p-0 m-0">Login now</a></p>
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
        $('#registrationForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Remove previous validation state
            $(this).removeClass('was-validated');

            // Perform form validation
            var isValid = this.checkValidity();
            var password = $('#password').val();
            var confirmPassword = $('#confirmPassword').val();

            if (password !== confirmPassword) {
                $('#confirmPassword').addClass('is-invalid');
                $('.invalid-feedback', '#confirmPassword').show();
                isValid = false;
            } else {
                $('#confirmPassword').removeClass('is-invalid');
                $('.invalid-feedback', '#confirmPassword').hide();
            }

            if (!isValid) {
                $(this).addClass('was-validated'); // Add validation state to show feedback
                return;
            }

            // Gather form data
            var formData = $(this).serialize();

            // Perform AJAX request
            $.ajax({
                url: 'registration_add_data.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.trim() === 'success') {
                        // Clear the form
                        $('#registrationForm')[0].reset();

                        // Show success message with SweetAlert2
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registration successful!',
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true
                        });

                        // Redirect to login page after a delay
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 1600);
                    } else {
                        // Handle non-success responses
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Registration failed: ' + response,
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'An error occurred: ' + error,
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                }
            });
        });
    });
</script>

</body>
</html>
