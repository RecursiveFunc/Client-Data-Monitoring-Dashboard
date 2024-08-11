    <!DOCTYPE html>
    <html lang="en">

    <?php
    session_start(); // Mulai sesi atau lanjutkan sesi yang ada
    // Check if the user is already logged in
    if (isset($_SESSION['jenis_role'])) {
        // Redirect the user to the dashboard or appropriate page based on their role
        if ($_SESSION['jenis_role'] === 1) {
            header('Location: /admin/dashboard.php');
            exit();
        } else {
            header('Location: sign_up.php');
            exit();
        }
    }

    include("../includes/head.php");
    include("../includes/header.php");
    ?>

    <style>
        .title-log {
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
        }

        .login-form {
            border: 1px solid #ad6018;
            padding: 50px;
            background-color: #fc8c03;
            border-radius: 20px;
            box-shadow: 5px 5px 6px rgba(142, 87, 0, 0.8);
        }

        .login-form h2 {
            margin-bottom: 15px;
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
                    <form id="loginForm" method="post" class="login-form">
                        <h2 class="title-log">LOG IN</h2>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" type="text" name="username" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" class="form-control">
                        </div>
                        <div id="loginMessage"></div>
                        <div class="mb-3">
                            <div id="link" class="form-text">Need an account?</div>
                            <a href="sign_up.php" class="link-primary">Sign up</a>
                        </div>
                        <button type="submit" class="btn btn-primary bacillus">Sign In</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Failure Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm d-flex align-items-center justify-content-center" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title" id="errorModalLabel">Login Failed</h5>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#loginForm').submit(function(event) {
                    event.preventDefault(); // Prevent form submission

                    // Get the form data
                    var username = $('#username').val();
                    var password = $('#password').val();

                    // Send an AJAX request to the PHP file for authentication
                    $.ajax({
                        url: '../components/getUser.php',
                        method: 'POST',
                        data: {
                            username: username,
                            password: password
                        },
                        success: function(response) {

                            var result = JSON.parse(response);

                            if (result.success) {
                                // Redirect to the home or another page on successful login
                                window.location.href = '../admin/dashboard.php';
                            } else {
                                // Display an error message
                                $('#errorMessage').text(result.message);
                                $('#errorModal').modal('show');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Display an error message
                            $('#errorMessage').text('Sorry, an unexpected error occurred.\n Please try again later.');
                            $('#errorModal').modal('show');
                        }
                    });
                });
            });
        </script>
    </body>

    </html>