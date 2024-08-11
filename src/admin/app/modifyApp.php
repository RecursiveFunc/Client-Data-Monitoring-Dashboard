<?php

include('../../database/pgconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $app = $_POST['Application'];
    $pekId = $_POST['Pekerjaan'];

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE app SET app=:app, jenis_pekerjaan=:pekId WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':app', $app, PDO::PARAM_STR);
    $stmt->bindParam(':pekId', $pekId, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $response = array();

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['title'] = "App updated";
        $response['message'] = 'App updated successfully.';
    } else {
        $response['status'] = 'error';
        $response['title'] = "App not updated";
        $response['message'] = 'Failed to update App.';
    }

    echo json_encode($response);
}
