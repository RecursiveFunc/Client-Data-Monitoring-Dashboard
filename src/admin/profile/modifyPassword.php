<?php
session_start();
include('../../database/pgconn.php'); // Include PostgreSQL database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];

    try {
        // Fetch the old hashed password from the database
        $selectStmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $selectStmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $selectStmt->execute();

        $storedHashedPassword = $selectStmt->fetchColumn();

        // Verify old password using password_verify
        if ($storedHashedPassword && password_verify($oldPassword, $storedHashedPassword)) {
            // Old password matches, proceed to update the password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateStmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $updateStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $updateStmt->execute();

            $response = [
                'status' => 'success',
                'title' => 'Password updated',
                'message' => 'Password updated successfully.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'title' => 'Invalid old password',
                'message' => 'The old password provided is incorrect.'
            ];
        }
    } catch (PDOException $e) {
        $response = [
            'status' => 'error',
            'title' => 'Database error',
            'message' => 'Error: ' . $e->getMessage()
        ];
    }

    echo json_encode($response);
}
