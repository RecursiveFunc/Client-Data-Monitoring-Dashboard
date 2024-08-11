<?php

include('../../database/pgconn.php');

$sql = "SELECT * FROM pekerjaan";
$stmt = $conn->query($sql);

if ($stmt) {
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} else {
    // Handle the error, if any
    $errorInfo = $conn->errorInfo();
    echo json_encode(['error' => 'Failed to execute query: ' . $errorInfo[2]]);
}
