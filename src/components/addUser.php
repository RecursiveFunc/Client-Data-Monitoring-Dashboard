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

    // Use $conn (PDO instance) directly
    $result = $conn->query($sql);

    if ($result) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->errorInfo()[2];
    }
}

// Close the database connection
// No need to close the connection, as it's a PDO instance
