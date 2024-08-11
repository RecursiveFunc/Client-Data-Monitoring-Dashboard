<?php

include('../../database/pgconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['Username'];
    $pekId = $_POST['Pekerjaan'];
    $email = $_POST['Email'];
    $roleId = $_POST['Role'];

    $sql = "UPDATE users SET username=:username, 
                                jenis_pekerjaan=:pekId, 
                                email=:email, 
                                jenis_role=:roleId WHERE id=:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->bindParam(":pekId", $pekId, PDO::PARAM_INT);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":roleId", $roleId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['title'] = "User updated";
        $response['message'] = 'User updated successfully.';
    } else {
        $response['status'] = 'error';
        $response['title'] = "User not updated";
        $response['message'] = 'Failed to update user.';
    }

    echo json_encode($response);
}
