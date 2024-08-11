<?php
// path-to-your-server-side-script.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $identifier = $_POST['identifier'];
    $log_date = $_POST['log_date'];
    $data = $_POST['data'];

    // Include the database connection file
    include('../../database/pgconn.php'); // Include PostgreSQL database connection script

    // Prepare statement
    $query = "
        INSERT INTO activity_log (identifier, log_date, data)
        VALUES (:identifier, :log_date, :data)
        ON CONFLICT (identifier, log_date)
        DO UPDATE SET data = :data;
    ";

    $stmt = $conn->prepare($query);

    // Bind values to parameters
    $stmt->bindParam(':identifier', $identifier);
    $stmt->bindParam(':log_date', $log_date);
    $stmt->bindParam(':data', $data);

    // Execute the prepared statement
    try {
        $stmt->execute();
        echo "Data berhasil dimasukkan atau diperbarui di dalam tabel activity_log.";
    } catch (PDOException $e) {
        die("Query gagal: " . $e->getMessage());
    }

    // Close connection
    $conn = null;
} else {
    // Tanggapan jika metode tidak diizinkan
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Method Not Allowed';
}
