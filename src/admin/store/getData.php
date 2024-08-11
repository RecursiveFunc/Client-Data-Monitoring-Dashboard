<?php

include('../../database/pgconn.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "SELECT id, identifier, log_date, data
            FROM activity_log
            WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($result);
} else {
    $sql = "SELECT id, identifier, log_date, data
            FROM activity_log";
    $stmt = $conn->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
}
