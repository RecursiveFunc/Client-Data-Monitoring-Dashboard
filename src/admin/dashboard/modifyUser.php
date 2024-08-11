<?php

include('../../database/pgconn.php');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $user = $_POST['Username'];
        $slug = $_POST['Slug'];
        $pekId = $_POST['Pekerjaan'];

        $stmt = $conn->prepare("UPDATE dashboard SET \"user\"=:user, slug=:slug, jenis_pekerjaan=:pekId WHERE id=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":user", $user, PDO::PARAM_STR);
        $stmt->bindParam(":slug", $slug, PDO::PARAM_STR);
        $stmt->bindParam(":pekId", $pekId, PDO::PARAM_INT);
        $stmt->execute();

        $response['status'] = 'success';
        $response['title'] = "User PC updated";
        $response['message'] = 'User PC updated successfully.';
    } else {
        $response['status'] = 'error';
        $response['title'] = "User PC not updated";
        $response['message'] = 'Invalid request method.';
    }

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
