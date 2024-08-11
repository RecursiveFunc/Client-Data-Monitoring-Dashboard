<!doctype html>
<html lang="en">

<?php
include("../includes/head.php");
include("./includes/adminHeader.php");
$username = $_SESSION['username'];
?>

<!-- Include Feather Icons library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<body>
    <!-- <?php echo $username; ?> -->
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <?php include("./includes/adminSidebar.php"); ?>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Users</h1>
                </div>
                <h2>All users are in here</h2>
                <div class="table-responsive">
                    <button class="btn btn-success mt-3" id="newUser"><i class="bi bi-plus-lg"></i> Add New User</button>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Username</th>
                                <th scope="col">Job</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="content"></tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Add New User Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add New User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary" pekerjaan="alert" id="addMessage" style="display:none;"></div>
                    <form method="POST" id="addUserForm">
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="addUsername">Username</label>
                            <input type="text" name="Username" id="addUsername" class="form-control form-control" />
                            <small class="text-danger ml-5" id="addUsernameError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="addPekerjaan">Job</label>
                            <select class="form-select" id="addPekerjaan" aria-label="Floating label select example" name="Pekerjaan"></select>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="addEmail">Email</label>
                            <input type="email" name="Email" id="addEmail" class="form-control form-control" aria-describedby="eamilHelp" />
                            <small class="text-danger ml-5" id="addEmailError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="addRole">Role</label>
                            <select class="form-select" id="addRole" aria-label="Floating label select example" name="Role"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnAdd" type="button" class="btn btn-primary">Add User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Existing User Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Existing User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary" pekerjaan="alert" id="editMessage" style="display:none;"></div>
                    <form method="POST" id="editUserForm">
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editUsername">Username</label>
                            <input type="text" name="Username" id="editUsername" class="form-control form-control" />
                            <small class="text-danger ml-5" id="editUsernameError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editPekerjaan">Job</label>
                            <select class="form-select" id="editPekerjaan" aria-label="Floating label select example" name="Pekerjaan"></select>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editEmail">Email</label>
                            <input type="email" name="Email" id="editEmail" class="form-control form-control" />
                            <small class="text-danger ml-5" id="editEmailError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editRole">Role</label>
                            <select class="form-select" id="editRole" aria-label="Floating label select example" name="Role"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnSave" type="button" class="btn btn-primary">Save User Changes</button>
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

    <script>
        $(document).ready(function() {
            //* Load All Users *//
            $.ajax({
                url: '../admin/user/getUser.php',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    let row = '';
                    let i = 1;

                    // Check jenis_role from PHP session
                    var userJenisRole = <?php echo $_SESSION['jenis_role']; ?>;

                    $.each(data, function(key, value) {
                        row += '<tr>';
                        row += '<td>' + i + '</td>';
                        row += '<td>' + value.username + '</td>';
                        row += '<td>' + value.pekerjaan_description + '</td>';
                        row += '<td>' + value.email + '</td>';
                        row += '<td>' + value.role_description + '</td>';
                        row += '<td>';

                        // console.log(value.username);

                        // Check if userJenisRole is 1 for enabling edit and delete
                        if (userJenisRole === 1) {
                            row += '<button class="btnEdit btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' +
                                value.id + '"><i class="bi bi-pencil"></i> Edit</button> ';
                            row += '<button class="btnDelete btn btn-danger" data-id="' + value.id +
                                '"><i class="bi bi-trash"></i> Delete</button>';
                        } else if (userJenisRole === 2) {
                            // Check if the username matches
                            row += ('<?php echo $username; ?>' === value.username) ?
                                '<button class="btnEdit btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' + value.id + '"><i class="bi bi-pencil"></i> Edit</button> ' +
                                '<button class="btnDelete btn btn-danger" data-id="' + value.id + '"><i class="bi bi-trash"></i> Delete</button>' :
                                (value.role_description === 'user') ?
                                '<button class="btnEdit btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' + value.id + '"><i class="bi bi-pencil"></i> Edit</button> ' +
                                '<button class="btnDelete btn btn-danger" data-id="' + value.id + '"><i class="bi bi-trash"></i> Delete</button>' :
                                '<span class="text-danger">You don\'t have permission</span>';
                        } else if (userJenisRole === 3) {
                            // If userJenisRole is 3, allow edit and delete for role_description 'user' and 'admin'
                            row += '<button class="btnEdit btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' +
                                value.id + '"><i class="bi bi-pencil"></i> Edit</button> ';
                            row += '<button class="btnDelete btn btn-danger" data-id="' + value.id +
                                '"><i class="bi bi-trash"></i> Delete</button>';
                        } else {
                            // For any other cases
                            row += '<span class="text-danger">You don\'t have permission</span>';
                        }


                        row += '</td>';
                        row += '</tr>';
                        i++;
                    });
                    $('#content').html(row);
                }
            });

            //* Load Pekerjaan *//
            $.ajax({
                url: '../admin/user/getPekerjaan.php',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    let options = '';
                    $.each(data, function(key, value) {
                        options += '<option value="' + value.id + '">' + value.nama_pekerjaan +
                            '</option>';
                    });
                    $('#addPekerjaan').html(options);
                    $('#editPekerjaan').html(options);
                }
            });

            //* Load Role *//
            $.ajax({
                url: '../admin/user/getRole.php',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    let options = '';
                    $.each(data, function(key, value) {
                        options += '<option value="' + value.id + '">' + value.name +
                            '</option>';
                    });
                    $('#addRole').html(options);
                    $('#editRole').html(options);
                }
            });

            //* Add New User *//
            $('#newUser').click(function() {
                $('#addMessage').hide();
                $('#addUserForm')[0].reset();
                $('#addModal').modal('show');
            });

            //* Save New User *//
            $('#btnAdd').click(function() {
                $.ajax({
                    url: '../admin/user/addUser.php',
                    method: 'POST',
                    data: $('#addUserForm').serialize(),
                    success: function(response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        if (data.status === 'success') {
                            SuccessAlert(data.message);
                            setTimeout(function() {
                                $('#addModal').modal('hide');
                                location.reload();
                            }, 2000);
                        } else {
                            ErrorAlert(data.message);
                        }
                    }
                });
            });

            //* Edit Existing User *//
            $(document).on('click', '.btnEdit', function() {
                let userId = $(this).data('id');
                $.ajax({
                    url: '../admin/user/setUser.php',
                    method: 'POST',
                    data: {
                        id: userId
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#editUsername').val(data.username);
                        $('#editPekerjaan').val(data.jenis_pekerjaan);
                        $('#editEmail').val(data.email);
                        $('#editRole').val(data.jenis_role);
                        $('#editMessage').hide();
                        $('#btnSave').data('id', userId);
                        $('#editModal').modal('show');
                    }
                });
            });

            //* Save Modifying User *//
            $('#btnSave').click(function() {
                let userId = $(this).data('id');
                $.ajax({
                    url: '../admin/user/modifyUser.php',
                    method: 'POST',
                    data: $('#editUserForm').serialize() + '&id=' + userId,
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
            });

            //* Delete Product *//
            $(document).on('click', '.btnDelete', function() {
                let userId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to retrieve this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete user!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../admin/user/removeUser.php',
                            method: 'POST',
                            data: {
                                id: userId
                            },
                            success: function(response) {
                                console.log(response);
                                let data = JSON.parse(response);
                                if (data.status === 'success') {
                                    Swal.fire(
                                        'Deleted!',
                                        'User has been deleted.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete user.',
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            });

        });
    </script>

    <script>
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
    </script>

</body>

</html>