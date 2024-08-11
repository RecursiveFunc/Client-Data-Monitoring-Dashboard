<!DOCTYPE html>
<html lang="en">

<?php
session_start();

include("../includes/head.php");
include("./includes/adminHeader.php");

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Email not set'; // Check if email is set
?>

<!-- Include Feather Icons library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<body class="bg-gray-100">
    <div style="display: flex;">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-gray-500 sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <?php include("./includes/adminSidebar.php"); ?>
                </ul>
            </div>
        </nav>

        <div class="flex py-5 my-auto text-center mx-44" style="width: 100%;"> <!-- Lebar konten utama diatur 100% -->
            <div class="border bg-white dark:bg-gray-800 dark:border-gray-700 shadow-lg rounded-lg p-5 w-full md:w-1/2 mx-auto my-0">
                <div class="flex flex-col justify-center w-auto ml-4 h-50">
                    <p class="text-sm font-medium text-gray-600 font-dm">Your Profile</p>
                    <h4 class="text-xl font-bold dark:text-white"><?php echo $username ?></h4>
                    <h4 class="text-xl font-bold dark:text-white"><?php echo $email ?></h4>
                    <button class="border border-orange-500 bg-black-500 hover:bg-teal-700 text-orange-500 font-bold py-2 px-4 rounded mt-2" id="btnModal">Change Password</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Existing Password -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary" role="alert" id="editMessage" style="display:none;"></div>
                    <form method="POST" id="editPasswordForm">
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editoldPassword">Old Password</label>
                            <input type="password" name="oldPassword" id="editoldPassword" class="form-control form-control" />
                            <small class="text-danger ml-5" id="editoldPasswordError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editPassword">New Password</label>
                            <input type="password" name="Password" id="editPassword" class="form-control form-control" />
                            <small class="text-danger ml-5" id="editPasswordError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editnewPassword">New Password Confirmation</label>
                            <input type="password" name="newPassword" id="editnewPassword" class="form-control form-control" />
                            <small class="text-danger ml-5" id="editnewPasswordError"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnSave" type="button" class="btn btn-primary">Change Password</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
    </script>
    <!-- Include bcrypt.js library from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.2.0/bcrypt.min.js" async></script>

    <script>
        //* Edit User Password *//
        $('#btnModal').click(function() {
            $('#editMessage').hide();
            $('#editPasswordForm')[0].reset();
            $('#editModal').modal('show');
        });
    </script>

    <script>
        $('#btnSave').click(function() {
            let oldPassword = $('#editoldPassword').val();
            let newPassword = $('#editPassword').val();
            let confirmPassword = $('#editnewPassword').val();

            // Check if new password and confirm password match
            if (newPassword !== confirmPassword) {
                $('#editnewPasswordError').text('New password and confirmation do not match.');
                return;
            }

            // Send the plain text new password to the server
            $.ajax({
                url: '../admin/profile/modifyPassword.php',
                method: 'POST',
                data: {
                    oldPassword: oldPassword,
                    newPassword: newPassword
                },
                success: function(response) {
                    console.log(response);
                    let data = JSON.parse(response);
                    if (data.status === 'success') {
                        SuccessAlert(data.message);
                        setTimeout(function() {
                            $('#editModal').modal('hide');
                            location.reload();
                        }, 2000);
                    } else {
                        ErrorAlert(data.message);
                    }
                }
            });

            //* SweetAlert Success *//
            function SuccessAlert(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: message
                });
            }

            //* SweetAlert Error *//
            function ErrorAlert(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
        });
    </script>
</body>

</html>