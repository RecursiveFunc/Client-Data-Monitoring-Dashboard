<?php

include('../../database/pgconn.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT p.*, b.nama_pekerjaan AS pekerjaan_description
            FROM app p
            JOIN pekerjaan b ON p.jenis_pekerjaan = b.id
            WHERE p.id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} else {

    $sql = "SELECT p.*, b.nama_pekerjaan AS pekerjaan_description
            FROM app p
            JOIN pekerjaan b ON p.jenis_pekerjaan = b.id";
    $stmt = $conn->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
}
