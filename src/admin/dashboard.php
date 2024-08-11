<!DOCTYPE html>
<html>

<?php
session_start();

include("../includes/head.php");
include("./includes/adminHeader.php");

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$role = $_SESSION['jenis_role'];
?>

<!-- Include Feather Icons library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<body>
    <!-- <?php echo $username; ?>
    <?php echo $role; ?> -->
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
                    <h1 class="h2">Dashboard</h1>
                </div>
                <div class="table-responsive">
                    <button class="btn btn-success mt-3" id="newUser"><i class="bi bi-plus-lg"></i> Add New User PC</button>
                    <form method="POST" class="form-inline my-2 my-lg-0" id="searchForm">
                        <input class="form-control mr-sm-2" type="search" id="searchUsername" placeholder="Enter User PC, Slug or Job here..." aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="btnSearch">Search</button>
                    </form>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" class="sortable-column" id="user">
                                    User PC <i class="fas fa-sort"></i>
                                </th>
                                <th scope="col" class="sortable-column" id="slug">
                                    Slug <i class="fas fa-sort"></i>
                                </th>
                                <th scope="col" class="sortable-column" id="pekerjaan_description">
                                    Job <i class="fas fa-sort"></i>
                                </th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="content"></tbody>
                    </table>
                    <div id="pagination" class="mt-3"></div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add New User Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add New User PC</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary" pekerjaan="alert" id="addMessage" style="display:none;"></div>
                    <form method="POST" id="addUserForm">
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="addUsername">User PC</label>
                            <input type="text" name="Username" id="addUsername" class="form-control form-control" />
                            <small class="text-danger ml-5" id="addUsernameError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="addSlug">Slug</label>
                            <input type="slug" name="Slug" id="addSlug" class="form-control form-control" />
                            <small class="text-danger ml-5" id="addSlugError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="addPekerjaan">Job</label>
                            <select class="form-select" id="addPekerjaan" aria-label="Floating label select example" name="Pekerjaan"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnAdd" type="button" class="btn btn-primary">Add User PC</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Existing User Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Existing User PC</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary" pekerjaan="alert" id="editMessage" style="display:none;"></div>
                    <form method="POST" id="editUserForm">
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editUsername">User PC</label>
                            <input type="text" name="Username" id="editUsername" class="form-control form-control" />
                            <small class="text-danger ml-5" id="editUsernameError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editSlug">Slug</label>
                            <input type="text" name="Slug" id="editSlug" class="form-control form-control" />
                            <small class="text-danger ml-5" id="editSlugError"></small>
                        </div>
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editPekerjaan">Job</label>
                            <select class="form-select" id="editPekerjaan" aria-label="Floating label select example" name="Pekerjaan"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnSave" type="button" class="btn btn-primary">Save User PC Changes</button>
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
            // Load All Users
            $.ajax({
                url: '../admin/dashboard/getUser.php',
                dataType: 'json',
                success: function(data) {
                    // Store the original data for resetting
                    let originalData = data;

                    // Function to update the user list based on search criteria, sorting, and pagination
                    function updateList(searchTerm, page = 1, itemsPerPage = 10, sortBy = 'user', sortOrder = 'asc') {
                        let filteredData;

                        if (searchTerm.trim() === '' || searchTerm.length < 2) {
                            // If the search term is empty or less than 2 characters, show all users
                            filteredData = originalData;
                        } else {
                            // Otherwise, filter based on the search term
                            filteredData = originalData.filter(function(user) {
                                const searchLower = searchTerm.toLowerCase();
                                return (
                                    user.user.toLowerCase().includes(searchLower) ||
                                    user.slug.toLowerCase().includes(searchLower) ||
                                    user.pekerjaan_description.toLowerCase().includes(searchLower)
                                );
                            });
                        }

                        // Sort the data based on the selected column and order
                        filteredData.sort(function(a, b) {
                            // Check if the keys exist in the objects
                            if (a[sortBy] && b[sortBy]) {
                                const comparison = a[sortBy].localeCompare(b[sortBy]);
                                return sortOrder === 'asc' ? comparison : -comparison;
                            } else {
                                // If the keys are missing, return 0 to maintain the current order
                                return 0;
                            }
                        });

                        // Calculate the start and end index for the current page
                        const startIndex = (page - 1) * itemsPerPage;
                        const endIndex = startIndex + itemsPerPage;

                        // Get the current page data
                        const currentPageData = filteredData.slice(startIndex, endIndex);

                        let row = '';
                        let i = startIndex + 1;
                        $.each(currentPageData, function(key, value) {
                            row += '<tr>';
                            row += '<td>' + i + '</td>';

                            // Check if role is 1 and username matches
                            <?php if ($role == 1) : ?>
                                var username = '<?php echo $username; ?>';
                                if (username == value.user) {
                                    row += '<td>' + value.user + '</td>';
                                    row += '<td><a href="page.php/' + value.slug + '">' + value.slug + '</a></td>';
                                    row += '<td>' + value.pekerjaan_description + '</td>';
                                } else {

                                }
                            <?php else : ?>
                                row += '<td>' + value.user + '</td>';
                                row += '<td><a href="page.php/' + value.slug + '">' + value.slug + '</a></td>';
                                row += '<td>' + value.pekerjaan_description + '</td>';
                            <?php endif; ?>

                            <?php if ($role == 2 || $role == 3) : ?>
                                row +=
                                    '<td><button class="btnEdit btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' +
                                    value.id + '"><i class="bi bi-pencil"></i> Edit</button></td>';
                                row +=
                                    '<td><button class="btnDelete btn btn-danger" data-id="' + value.id +
                                    '"><i class="bi bi-trash"></i> Delete</button></td>';
                            <?php else : ?>
                                row += '<td><span class="text-danger">You don\'t have permission</span></td>';
                            <?php endif; ?>

                            row += '</td>';
                            row += '</tr>';
                            i++;
                        });
                        $('#content').html(row);

                        // Update pagination controls
                        updatePaginationControls(filteredData.length, page, itemsPerPage);

                        // Update the sorting icons
                        updateSortingIcons(sortBy, sortOrder);
                    }

                    // Initial rendering of user list and pagination controls
                    updateList('');

                    // Handle input changes for searching
                    $('#searchUsername').on('input', function() {
                        const searchTerm = $(this).val();
                        updateList(searchTerm);
                    });

                    // Function to update pagination controls
                    function updatePaginationControls(totalItems, currentPage, itemsPerPage) {
                        const totalPages = Math.ceil(totalItems / itemsPerPage);

                        let paginationHTML = '<ul class="pagination">';
                        for (let i = 1; i <= totalPages; i++) {
                            paginationHTML += '<li class="page-item ' + (i === currentPage ? 'active' : '') + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                        }
                        paginationHTML += '</ul>';

                        $('#pagination').html(paginationHTML);

                        // Handle pagination link clicks
                        $('.page-link').on('click', function(e) {
                            e.preventDefault();
                            const page = parseInt($(this).data('page'));
                            updateList($('#searchUsername').val(), page);
                        });
                    }

                    // Function to update sorting icons
                    function updateSortingIcons(sortBy, sortOrder) {
                        // Remove existing sorting icons
                        $('.sortable-column').removeClass('sorted-asc sorted-desc');

                        // Add the appropriate sorting icon to the selected column
                        $('#' + sortBy).addClass('sorted-' + sortOrder);
                    }

                    // Handle sorting column clicks
                    $('.sortable-column').on('click', function() {
                        const columnId = $(this).attr('id');
                        const currentSortOrder = $(this).hasClass('sorted-asc') ? 'asc' : 'desc';

                        // Toggle the sorting order
                        const newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';

                        // Update the user list with the new sorting parameters
                        updateList($('#searchUsername').val(), 1, 10, columnId, newSortOrder);
                    });
                }
            });



            //* Load Pekerjaan *//
            $.ajax({
                url: '../admin/dashboard/getPekerjaan.php',
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

            //* Add New User *//
            $('#newUser').click(function() {
                if (<?php echo $_SESSION['jenis_role']; ?> !== 1) { // Check if role is admin
                    $('#addMessage').hide();
                    $('#addUserForm')[0].reset();
                    $('#addModal').modal('show');
                } else {
                    alert('You do not have permission to add new user PC.');
                }
            });

            //* Save New User *//
            $('#btnAdd').click(function() {
                if (<?php echo $_SESSION['jenis_role']; ?> !== 1) { // Check if role is admin
                    $.ajax({
                        url: '../admin/dashboard/addUser.php',
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
                } else {
                    alert('You do not have permission to add new user PC.');
                }
            });

            //* Edit Existing User *//
            $(document).on('click', '.btnEdit', function() {
                if (<?php echo $_SESSION['jenis_role']; ?> !== 1) { // Check if role is admin
                    let userId = $(this).data('id');
                    $.ajax({
                        url: '../admin/dashboard/setUser.php',
                        method: 'POST',
                        data: {
                            id: userId
                        },
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                            $('#editUsername').val(data.user);
                            $('#editPekerjaan').val(data.jenis_pekerjaan);
                            $('#editSlug').val(data.slug);
                            $('#editMessage').hide();
                            $('#btnSave').data('id', userId);
                            $('#editModal').modal('show');
                        }
                    });
                } else {
                    alert('You do not have permission to edit user PC.');
                }
            });

            //* Save Modifying User *//
            $('#btnSave').click(function() {
                if (<?php echo $_SESSION['jenis_role']; ?> !== 1) { // Check if role is admin
                    let userId = $(this).data('id');
                    $.ajax({
                        url: '../admin/dashboard/modifyUser.php',
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
                } else {
                    alert('You do not have permission to edit user PC.');
                }
            });

            //* Delete User PC *//
            $(document).on('click', '.btnDelete', function() {
                if (<?php echo $_SESSION['jenis_role']; ?> !== 1) { // Check if role is admin
                    let userId = $(this).data('id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to retrieve this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete User PC!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '../admin/dashboard/removeUser.php',
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
                                            'Your User PC has been deleted.',
                                            'success'
                                        ).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'Failed to delete the User PC.',
                                            'error'
                                        );
                                    }
                                }
                            });
                        }
                    });
                } else {
                    alert('You do not have permission to delete user PC.');
                }
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

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
    </script>

</body>

</html>