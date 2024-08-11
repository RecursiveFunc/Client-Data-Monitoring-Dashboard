<?php

include('../../database/pgconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $pekerjaan = $_POST['Pekerjaan'];

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE pekerjaan SET nama_pekerjaan = :pekerjaan WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pekerjaan', $pekerjaan, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $response = array();

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['title'] = "Pekerjaan updated";
        $response['message'] = 'Job updated successfully.';
    } else {
        $response['status'] = 'error';
        $response['title'] = "Pekerjaan not updated";
        $response['message'] = 'Failed to update Job.';
    }

    echo json_encode($response);
}
