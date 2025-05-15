<?php

include('../../database/pgconn.php');

$sql = "SELECT * FROM jobs";
$stmt = $conn->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
