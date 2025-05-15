<?php

include('../database/pgconn.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $conn; // Assuming $conn is your PDO instance

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $jenis_role = 1; // User
    $jenis_pekerjaan = 1; // Belum assigned pekerjaan

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Modify the SQL query for PostgreSQL
    $sql = "INSERT INTO users (username, email, password, jenis_role, jenis_pekerjaan) VALUES ('$username', '$email', '$hashed_password', $jenis_role, '$jenis_pekerjaan')";

    // Check if the connection is valid before using it
    if ($conn) {
        try {
            // Use $conn (PDO instance) directly
            $result = $conn->query($sql);

            if ($result) {
                echo "Registration successful!";
            } else {
                echo "Error: " . $sql . "<br>Query Error"; //Simplified error
            }
        } catch (PDOException $e) {
            echo "Error: " . $sql . "<br>Query Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Database connection is invalid."; // Handle the case where $conn is null
    }
}

// Close the database connection
// No need to close the connection, as it's a PDO instance
