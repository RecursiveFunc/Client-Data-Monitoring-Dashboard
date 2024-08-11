<?php

include('../../database/pgconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app = $_POST['Application'];
    $pekId = $_POST['Pekerjaan'];

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO app (app, jenis_pekerjaan) VALUES (:app, :pekId)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':app', $app, PDO::PARAM_STR);
    $stmt->bindParam(':pekId', $pekId, PDO::PARAM_INT);

    $response = array();

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['title'] = "App added";
        $response['message'] = 'App added successfully.';
    } else {
        $response['status'] = 'error';
        $response['title'] = "App not added";
        $response['message'] = 'Failed to add app.';
    }

    echo json_encode($response);
}
