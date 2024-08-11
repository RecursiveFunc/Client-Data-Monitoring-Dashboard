<?php

include('../../database/pgconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $pekId = $_POST['Pekerjaan'];
    $email = $_POST['Email'];
    $roleId = $_POST['Role'];

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO users (username, jenis_pekerjaan, email, jenis_role) VALUES (:username, :pekId, :email, :roleId)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':pekId', $pekId, PDO::PARAM_INT);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);

    $response = array();

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['title'] = "User added";
        $response['message'] = 'User added successfully.';
    } else {
        $response['status'] = 'error';
        $response['title'] = "User not added";
        $response['message'] = 'Failed to add user.';
    }

    echo json_encode($response);
}
