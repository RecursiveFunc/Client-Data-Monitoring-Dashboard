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

        .login-form {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .login-form:hover {
            transform: translateY(-5px);
        }

        .title-log {
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
                    <form id="loginForm" method="post" class="login-form">
                        <h2 class="title-log">Sign In</h2>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" class="form-control" required>
                        </div>
                        <div id="loginMessage"></div>
                        <div class="mb-3 text-center">
                            <small class="form-text">Don't have an account? <a href="sign_up.php" class="link-primary">Sign up</a></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign In</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Login Failed</h5>
                    </div>
                    <div class="modal-body">
                        <p id="errorMessage">Invalid username or password. Please try again.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
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