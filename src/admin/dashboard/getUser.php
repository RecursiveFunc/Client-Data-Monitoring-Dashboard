<?php

include('../../database/pgconn.php');

try {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("SELECT p.*, b.nama_pekerjaan AS pekerjaan_description
            FROM dashboard p
            JOIN pekerjaan b ON p.jenis_pekerjaan = b.id
            WHERE p.id=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    } else {
        $stmt = $conn->query("SELECT p.*, b.nama_pekerjaan AS pekerjaan_description
            FROM dashboard p
            JOIN pekerjaan b ON p.jenis_pekerjaan = b.id");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
