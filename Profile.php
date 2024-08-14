<?php
session_start(); // Start the session

// Check if the user is logged in
$is_logged_in = isset($_SESSION['auth']) && $_SESSION['auth'] === true;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="asset/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>My Blog</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->
</head>
<body class="bg-light">
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h2 class="text-center pt-4">My Blog</h2>
            </div>
        </div>

        <div class="nav-sec">
            <div class="container">
                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12">
                    <nav class="navbar navbar-dark navbar-expand justify-content-center bg-dark">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">Home</a></li>
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">Blog</a></li>
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">Contact</a></li>
                    <li class="nav-item"><a href="index.php" class="nav-link text-light">About</a></li>

                    <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                My Account
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a href="login.php" class="nav-link text-light">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row p-4">
            <div class="col-lg-8 col-md-7 mb-3">
                <div class="card mycard">
                    <h3 class="text-left ml-5 mt-3 pb-3">User Form</h3>
                    <form id="userForm" action="update_user.php" method="POST">
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

                    // Check if user_id is set in session
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];

                        // Fetch user data
                        $sql = "SELECT * FROM users WHERE id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo '
                                <div class="form-group ml-5 mr-5">
                                    <input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">
                                    <input type="text" value="'.htmlspecialchars($row['first_name']).'" placeholder="First Name" name="first_name" class="form-control">
                                    <small class="text-danger" id="firstNameError"></small>
                                </div>
                                <div class="form-group ml-5 mr-5">
                                    <input type="text" value="'.htmlspecialchars($row['last_name']).'" placeholder="Last Name" name="last_name" class="form-control">
                                    <small class="text-danger" id="lastNameError"></small>
                                </div>
                                <div class="form-group ml-5 mr-5">
                                    <input type="email" value="'.htmlspecialchars($row['email']).'" placeholder="Email" name="email" class="form-control">
                                    <small class="text-danger" id="emailError"></small>
                                </div>
                                <div class="form-group ml-5 mr-5">
                                    <input type="text" value="'.htmlspecialchars($row['contact_number']).'" placeholder="Contact Number" name="contact_number" class="form-control">
                                    <small class="text-danger" id="contactNumberError"></small>
                                </div>';
                            }
                        } else {
                            echo "<div class='alert alert-warning ml-5 mr-5'>No user data found.</div>";
                        }
                        $stmt->close();
                    } else {
                        echo "<div class='alert alert-warning ml-5 mr-5'>User ID not found in session.</div>";
                    }
                    $conn->close();
                    ?>
                    <div class="form-group d-flex ml-md-5 text-primary">
                        <button type="submit" class="btn bg-primary text-white">Update</button>
                    </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="card mycard mt-4">
                    <h3 class="text-left ml-5 mt-3 pb-3">Change Password</h3>
                    <form id="passwordForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="form-group ml-5 mr-5">
                            <input type="password" name="old_password" placeholder="Old Password" class="form-control">
                            <small class="text-danger" id="oldPasswordError"></small>
                        </div>
                        <div class="form-group ml-5 mr-5">
                            <input type="password" name="new_password" placeholder="New Password" class="form-control">
                            <small class="text-danger" id="newPasswordError"></small>
                        </div>
                        <div class="form-group ml-5 mr-5">
                            <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control">
                            <small class="text-danger" id="confirmPasswordError"></small>
                        </div>
                        <div class="form-group d-flex ml-5">
                            <button type="submit" class="btn bg-primary text-white">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4 col-md-5 mb-3">
                <div class="card mycard mb-4">
                    <img src="asset/img/img2.jpg" class="card-img-top" alt="Card image cap">
                    <div class="card-body">
                        <h3 class="card-title">My Name</h3>
                        <p class="card-text">Just me, myself and I, exploring the universe of unknownment. I have a heart of love and an interest in lorem ipsum and mauris neque quam blog. I want to share my world with you.</p>
                    </div>
                </div>
                <div class="card mycard mb-4">
                    <h3 class="card-header">Popular Post</h3>
                    <div class="row g-0">
                        <div class="col">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex">
                                        <img src="asset/img/i1.jpg" class="mr-3" alt="Placeholder Image" style="width: 50px; height: 50px;">
                                        <div>
                                            <h5 class="mb-1">Lorem</h5>
                                            <p class="mb-1" style="font-size: 1rem;">sed mattis nunc</p>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex">
                                        <img src="asset/img/i2.jpg" class="mr-3" alt="Placeholder Image" style="width: 50px; height: 50px;">
                                        <div>
                                            <h5 class="mb-1">Ipsum</h5>
                                            <p class="mb-1" style="font-size: 1rem;">Praes tinci sed</p>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex">
                                        <img src="asset/img/i3.jpg" class="mr-3" alt="Placeholder Image" style="width: 50px; height: 50px;">
                                        <div>
                                            <h5 class="mb-1">Dipsum</h5>
                                            <p class="mb-1" style="font-size: 1rem;">Lorem ipsum dipsum</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mycard">
                    <h3 class="card-header">Advertisement</h3>
                    <div class="card-body">
                        <p>Advertising goes here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
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
                echo "<script>
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Password changed successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error updating password: " . $conn->error . "',
                            showConfirmButton: false,
                            timer: 1500
                        });
                      </script>";
            }

            $stmt->close();
        } else {
            echo "<script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: 'New password and confirm password do not match.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Old password is incorrect.',
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>";
    }

    // Close the connection
    $conn->close();
}
?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

<!-- JavaScript Validation -->
<script>
document.getElementById('userForm').addEventListener('submit', function(event) {
    let isValid = true;

    // Clear previous error messages
    document.getElementById('firstNameError').textContent = '';
    document.getElementById('lastNameError').textContent = '';
    document.getElementById('emailError').textContent = '';
    document.getElementById('contactNumberError').textContent = '';

    // Validate first name
    const firstName = document.querySelector('[name="first_name"]').value.trim();
    if (!firstName) {
        document.getElementById('firstNameError').textContent = 'First name is required.';
        isValid = false;
    }

    // Validate last name
    const lastName = document.querySelector('[name="last_name"]').value.trim();
    if (!lastName) {
        document.getElementById('lastNameError').textContent = 'Last name is required.';
        isValid = false;
    }

    // Validate email
    const email = document.querySelector('[name="email"]').value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        document.getElementById('emailError').textContent = 'Email is required.';
        isValid = false;
    } else if (!emailPattern.test(email)) {
        document.getElementById('emailError').textContent = 'Invalid email format.';
        isValid = false;
    }

    // Validate contact number
    const contactNumber = document.querySelector('[name="contact_number"]').value.trim();
    if (!contactNumber) {
        document.getElementById('contactNumberError').textContent = 'Contact number is required.';
        isValid = false;
    }

    // If validation fails, prevent form submission
    if (!isValid) {
        event.preventDefault();
    }
});

document.getElementById('passwordForm').addEventListener('submit', function(event) {
    let isValid = true;

    // Clear previous error messages
    document.getElementById('oldPasswordError').textContent = '';
    document.getElementById('newPasswordError').textContent = '';
    document.getElementById('confirmPasswordError').textContent = '';

    // Validate old password
    const oldPassword = document.querySelector('[name="old_password"]').value.trim();
    if (!oldPassword) {
        document.getElementById('oldPasswordError').textContent = 'Old password is required.';
        isValid = false;
    }

    // Validate new password
    const newPassword = document.querySelector('[name="new_password"]').value.trim();
    if (!newPassword) {
        document.getElementById('newPasswordError').textContent = 'New password is required.';
        isValid = false;
    }

    // Validate confirm password
    const confirmPassword = document.querySelector('[name="confirm_password"]').value.trim();
    if (!confirmPassword) {
        document.getElementById('confirmPasswordError').textContent = 'Confirm password is required.';
        isValid = false;
    } else if (newPassword !== confirmPassword) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
        isValid = false;
    }

    // If validation fails, prevent form submission
    if (!isValid) {
        event.preventDefault();
    }
});
</script>
</body>
</html>
