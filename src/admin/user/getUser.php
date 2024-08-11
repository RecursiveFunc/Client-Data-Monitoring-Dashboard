<?php

include('../../database/pgconn.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT p.*, b.nama_pekerjaan AS pekerjaan_description, r.name AS role_description
            FROM users p
            JOIN pekerjaan b ON p.jenis_pekerjaan = b.id
            JOIN role r ON p.jenis_role = CAST(r.id AS VARCHAR)
            WHERE p.id=:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        die('Error in SQL query: ' . print_r($stmt->errorInfo(), true));
    }

    $stmt->closeCursor();
} else {
    $sql = "SELECT p.*, b.nama_pekerjaan AS pekerjaan_description, r.name AS role_description
            FROM users p
            JOIN pekerjaan b ON p.jenis_pekerjaan = b.id
            JOIN role r ON p.jenis_role = CAST(r.id AS VARCHAR)";

    $stmt = $conn->query($sql);

    if ($stmt) {
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } else {
        die('Error in SQL query: ' . print_r($conn->errorInfo(), true));
    }
}
