<!DOCTYPE html>
<html lang="en">

<?php
include("../includes/head.php");
include("../includes/header.php");
?>

<style>
    .title-regis {
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    }

    .regis-form {
        border: 1px solid #ad6018;
        padding: 45px;
        background-color: #fc8c03;
        border-radius: 20px;
        box-shadow: 5px 5px 6px rgba(142, 87, 0, 0.8);
    }

    .regis-form h2 {
        margin-bottom: 5px;
    }

    .btn-primary {
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
        display: inline-block;
        padding: 10px 20px;
        border-radius: 50px;
        background-color: #F76B1C;
        color: #050505;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .bacillus {
        width: 100%;
        /* Set the width to 100% */
    }
</style>

<body class="text-center">
    <div class="container pt-5">
        <div class="row">
            <div class="mx-auto col-10 col-md-8 col-lg-6">
                <div class="alert alert-primary" role="alert" id="addMessage" style="display:none;"></div>
                <form method="POST" id="regisForm" class="regis-form">
                    <h2 class="title-regis">REGISTER</h2>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="username" name="username" class="form-control" id="username" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password">
                    </div>
                    <!-- Add the hidden input for the verification token -->
                    <input type="hidden" name="verification_token" id="verification_token">
                    <div class="mb-3">
                        <div id="link" class="form-text">Already have an account?</div>
                        <a href="sign_in.php" class="link-primary">Sign In</a>
                    </div>
                    <button id="btnAdd" type="submit" class="btn btn-primary bacillus">Sign up</button>
                </form>

                <div id="message"></div>
            </div>
        </div>
    </div>

    <!-- Failure Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Sign Up Failed</h5>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
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