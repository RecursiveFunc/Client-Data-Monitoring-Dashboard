<?php

include('../../database/pgconn.php');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];

        $stmt = $conn->prepare("SELECT * FROM dashboard WHERE id=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Record not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request. ID not provided.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
