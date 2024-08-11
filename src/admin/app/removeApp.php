<?php

include('../../database/pgconn.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Use prepared statement to prevent SQL injection
    $sql = "DELETE FROM app WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $response = array();

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'App deleted successfully.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete App.';
    }

    echo json_encode($response);
}
