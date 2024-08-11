<?php

include('../../database/pgconn.php');

try {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM dashboard WHERE id=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $response['status'] = 'success';
        $response['message'] = 'User PC deleted successfully.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid request. ID not provided.';
    }

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
