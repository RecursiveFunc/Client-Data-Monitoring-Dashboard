<!doctype html>
<html lang="en">

<?php
include("../includes/head.php");
include("./includes/adminHeader.php");
?>

<!-- Include Feather Icons library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

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
                    <h1 class="h2">Job</h1>
                </div>
                <h2>All Job is show here</h2>
                <div class="table-responsive">
                    <button class="btn btn-success mt-3" id="newPekerjaan"><i class="bi bi-plus-lg"></i> Add New Job Type</button>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Job Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="content"></tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Add New Location Categories Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Job</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary" pekerjaan="alert" id="addMessage" style="display:none;"></div>
                    <form method="POST" id="addPekerjaanForm">
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="addPekerjaan">Job Name</label>
                            <input type="text" name="Pekerjaan" id="addPekerjaan" class="form-control form-control" />
                            <small class="text-danger ml-5" id="addPekerjaanError"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnAdd" type="button" class="btn btn-primary">Add Job</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Existing Pekerjaan Categories Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Existing Job</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary" pekerjaan="alert" id="editMessage" style="display:none;"></div>
                    <form method="POST" id="editPekerjaanForm">
                        <div class="form-outline form-dark mb-4">
                            <label class="form-label" for="editPekerjaan">Job Name</label>
                            <input type="text" name="Pekerjaan" id="editPekerjaan" class="form-control form-control" />
                            <small class="text-danger ml-5" id="editPekerjaanError"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnSave" type="button" class="btn btn-primary">Save Job Changes</button>
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
            //* Load All Pek  *//
            $.ajax({
                url: '../admin/pekerjaan/getPekerjaan.php',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    let row = '';
                    let i = 1;
                    $.each(data, function(key, value) {
                        row += '<tr>';
                        row += '<td>' + i + '</td>';
                        row += '<td>' + value.nama_pekerjaan + '</td>';
                        row += '<td>';
                        row += '<button class="btnEdit btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' + value.id + '"><i class="bi bi-pencil"></i> Edit</button> ';
                        row += '<button class="btnDelete btn btn-danger" data-id="' + value.id + '"><i class="bi bi-trash"></i> Delete</button>';
                        row += '</td>';
                        row += '</tr>';
                        i++;
                    });
                    $('#content').html(row);

                    // Event delegation for the edit and delete buttons
                    $('#content').on('click', '.btnEdit', function() {
                        let pekId = $(this).data('id');
                        // Rest of your edit button code
                    });

                    $('#content').on('click', '.btnDelete', function() {
                        let pekId = $(this).data('id');
                        // Rest of your delete button code
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                }
            });


            //* Add New Pekerjaan Cat *//
            $('#newPekerjaan').click(function() {
                $('#addMessage').hide();
                $('#addPekerjaanForm')[0].reset();
                $('#addModal').modal('show');
            });

            //* Save New Pekerjaan Cat *//
            $('#btnAdd').click(function() {
                $.ajax({
                    url: '../admin/pekerjaan/addPekerjaan.php',
                    method: 'POST',
                    data: $('#addPekerjaanForm').serialize(),
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

            //* Edit Existing Pekerjaan Cat *//
            $(document).on('click', '.btnEdit', function() {
                let pekId = $(this).data('id');
                $.ajax({
                    url: '../admin/pekerjaan/setPekerjaan.php',
                    method: 'POST',
                    data: {
                        id: pekId
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#editPekerjaan').val(data.nama_pekerjaan);
                        $('#editMessage').hide();
                        $('#btnSave').data('id', pekId);
                        $('#editModal').modal('show');
                    }
                });
            });


            //* Save Modifying Pekerjaan Cat *//
            $('#btnSave').click(function() {
                let locId = $(this).data('id');
                $.ajax({
                    url: '../admin/pekerjaan/modifyPekerjaan.php',
                    method: 'POST',
                    data: $('#editPekerjaanForm').serialize() + '&id=' + locId,
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


            //* Delete Location Cat *//
            $(document).on('click', '.btnDelete', function() {
                let loccatId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to retrieve this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete this Job!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../admin/pekerjaan/removePekerjaan.php',
                            method: 'POST',
                            data: {
                                id: loccatId
                            },
                            success: function(response) {
                                console.log(response);
                                let data = JSON.parse(response);
                                if (data.status === 'success') {
                                    Swal.fire(
                                        'Deleted!',
                                        'This Job has been deleted.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete this Job.',
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