<!DOCTYPE html>
<html lang="en">

<?php
include("../includes/head.php");
?>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f9f9f9, #ffe4c4);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .regis-form {
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .regis-form:hover {
        transform: translateY(-5px);
    }

    .title-regis {
        font-weight: 600;
        font-size: 28px;
        margin-bottom: 25px;
        color: #ff7b00;
        text-align: center;
    }

    .form-control {
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 14px;
    }

    .btn-primary {
        background: linear-gradient(to right, #ff7b00, #ffb347);
        border: none;
        padding: 12px;
        width: 100%;
        font-weight: 600;
        font-size: 15px;
        border-radius: 50px;
        transition: background 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #e06e00, #ffa236);
    }

    .form-text a {
        text-decoration: none;
        font-weight: 500;
    }

    .modal-content {
        border-radius: 12px;
    }

    .modal-header {
        background-color: #ffefeb;
        border-bottom: none;
    }

    .modal-title {
        color: #d9534f;
        font-weight: 600;
    }
</style>

<body class="text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="alert alert-primary" role="alert" id="addMessage" style="display:none;"></div>
                <form method="POST" id="regisForm" class="regis-form">
                    <h2 class="title-regis">Sign Up</h2>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <!-- Hidden token field -->
                    <input type="hidden" name="verification_token" id="verification_token">
                    <div class="mb-3 text-center">
                        <small class="form-text">Already have an account? <a href="sign_in.php" class="link-primary">Sign in</a></small>
                    </div>
                    <button id="btnAdd" type="submit" class="btn btn-primary">Sign Up</button>
                </form>
                <div id="message"></div>
            </div>
        </div>
    </div>

    <!-- Failure Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Sign Up Failed</h5>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#regisForm').submit(function(event) {
                event.preventDefault(); // Prevent form submission

                // Get the form data
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (username === '' || email === '' || password === '') {
                    $('#errorMessage').text('Please fill in all the fields.');
                    $('#errorModal').modal('show');
                    return;
                }

                // Send an AJAX request to the PHP file for registration
                $.ajax({
                    url: '../components/addUser.php',
                    method: 'POST',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        $('#regisForm')[0].reset(); // Clear the form
                        $('#addMessage').text('Registration successful!'); // Display success message
                        $('#addMessage').show(); // Show the success message
                        // Redirect to the home or another page on successful login
                        window.location.href = 'sign_in.php';
                    },
                    error: function(xhr, status, error) {
                        // Display a more specific error message
                        $('#errorMessage').text('Registration failed. Please try again later.');
                        $('#errorModal').modal('show');

                        // Log the error details to the console for debugging
                        console.error(xhr, status, error);
                    }
                });
            });
        });
    </script>

</body>

</html>