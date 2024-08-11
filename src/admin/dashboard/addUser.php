<?php

include('../../database/pgconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['Username'];
    $slug = $_POST['Slug'];
    $pekId = $_POST['Pekerjaan'];

    try {
        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare('INSERT INTO dashboard ("user", slug, jenis_pekerjaan) VALUES (:user, :slug, :pekId)');
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':pekId', $pekId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['title'] = "User PC added";
            $response['message'] = 'User PC added successfully.';
        } else {
            $response['status'] = 'error';
            $response['title'] = "User PC not added";
            $response['message'] = 'Failed to add user PC.';
        }
    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['title'] = "Database error";
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    echo json_encode($response);
}
