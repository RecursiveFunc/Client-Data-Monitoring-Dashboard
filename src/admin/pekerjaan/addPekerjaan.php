<?php

include('../../database/pgconn.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the input name is 'Pekerjaan'
    $pekerjaan = $_POST['Pekerjaan'];

    try {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO pekerjaan (nama_pekerjaan) VALUES (:pekerjaan)");
        $stmt->bindParam(':pekerjaan', $pekerjaan);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['title'] = "Job added";
            $response['message'] = 'Job added successfully.';
        } else {
            $response['status'] = 'error';
            $response['title'] = "Job not added";
            $response['message'] = 'Failed to add Job.';
        }

        // Check for specific database errors
        $pdoErrorInfo = $stmt->errorInfo();
        if ($pdoErrorInfo[0] !== '00000') {
            $response['status'] = 'error';
            $response['title'] = "Database Error";
            $response['message'] = 'Failed to execute query: ' . $pdoErrorInfo[2];
        }
    } catch (PDOException $e) {
        $response['status'] = 'error';
        $response['title'] = "Database Error";
        $response['message'] = 'Failed to execute query: ' . $e->getMessage();
    }

    echo json_encode($response);
}

// Close the database connection
$conn = null;
