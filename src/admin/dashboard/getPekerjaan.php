<?php

include('../../database/pgconn.php');

try {
    // Use a prepared statement for better security
    $stmt = $conn->prepare("SELECT * FROM pekerjaan");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
