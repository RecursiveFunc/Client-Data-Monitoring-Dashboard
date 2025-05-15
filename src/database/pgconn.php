<?php
$host = "localhost"; // Or your specific host
$port = "5432"; // Default PostgreSQL port
$dbname = "ActivityMonitoring"; // Your database name
$user = "postgres"; // Your PostgreSQL username
$password = "root"; // Your PostgreSQL password

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

try {
    // Create a PDO instance
    $conn = new PDO($dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exceptions
} catch (PDOException $e) {
    // Handle connection errors robustly
    die("Connection failed: " . $e->getMessage()); // Stop execution on failure.  Important.
}
