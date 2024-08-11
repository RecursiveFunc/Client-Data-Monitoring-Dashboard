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
                    <h1 class="h2">Summary</h1>
                </div>
                <h2>All users summary are in here</h2>
                <div class="table-responsive">
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
                            </tr>
                        </thead>
                        <tbody id="content"></tbody>
                    </table>
                    <div id="pagination" class="mt-3"></div>
                </div>
            </main>
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
                            row += '<td>' + value.user + '</td>';
                            row += '<td><a href="pie.php/' + value.slug + '">' + value.slug + '</a></td>';
                            row += '<td>' + value.pekerjaan_description + '</td>';
                            row += '<td>';
                            row += '</td>';
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