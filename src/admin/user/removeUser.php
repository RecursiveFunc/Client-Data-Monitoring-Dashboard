<?php
include('../../database/pgconn.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM users WHERE id=:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    $response = array();

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'User deleted successfully.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete user.';
    }

    echo json_encode($response);
}
