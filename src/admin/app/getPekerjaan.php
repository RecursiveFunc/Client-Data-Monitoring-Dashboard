<?php

include('../../database/pgconn.php');

$sql = "SELECT * FROM pekerjaan";
$stmt = $conn->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
