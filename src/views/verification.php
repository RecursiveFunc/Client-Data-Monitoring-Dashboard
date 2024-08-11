<!DOCTYPE html>
<html lang="en">

<?php
include("../includes/head.php");
include("../includes/header.php");
?>

<style>
    .title-verification {
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    }

    .verification-form {
        border: 1px solid #ad6018;
        padding: 45px;
        background-color: #fc8c03;
        border-radius: 20px;
        box-shadow: 5px 5px 6px rgba(142, 87, 0, 0.8);
    }

    .verification-form h2 {
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
                <div class="alert alert-primary" role="alert" id="verificationMessage" style="display:none;"></div>
                <form method="POST" id="verificationForm" class="verification-form">
                    <h2 class="title-verification">Email Verification</h2>
                    <p>Please enter the 6-digit verification code sent to your email.</p>
                    <div class="mb-3">
                        <label for="verificationCode" class="form-label">Verification Code</label>
                        <input type="text" name="verificationCode" class="form-control" id="verificationCode">
                    </div>
                    <button id="btnVerify" type="submit" class="btn btn-primary bacillus">Verify</button>
                </form>

                <div id="verificationResult"></div>
            </div>
        </div>
    </div>

    <!-- Failure Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Verification Failed</h5>
                </div>
                <div class="modal-body">
                    <p id="verificationErrorMessage"></p>
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
            $('#verificationForm').submit(function(event) {
                event.preventDefault(); // Prevent form submission

                // Get the entered verification code
                var verificationCode = $('#verificationCode').val();

                // Send an AJAX request to the PHP file for verification
                $.ajax({
                    url: '../components/verifyUser.php',
                    method: 'POST',
                    data: {
                        token: verificationCode
                    },
                    success: function(response) {
                        var result = JSON.parse(response);

                        if (result.success) {
                            // Display success message
                            $('#verificationMessage').text('Verification successful! You can now log in.');
                            $('#verificationMessage').show();

                            // Redirect to the home or another page on successful login
                            window.location.href = 'sign_in.php';
                        } else {
                            // Display an error message
                            $('#verificationErrorMessage').text(result.message);
                            $('#errorModal').modal('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Display a more specific error message
                        $('#verificationErrorMessage').text('Verification failed. Please try again later.');
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