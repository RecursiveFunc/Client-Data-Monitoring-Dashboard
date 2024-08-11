<?php
// Start the session at the beginning of the script
session_start();

require_once '../database/pgconn.php';

// Get user record based on username
function getUser($username)
{
    /** @var \PgSql\Connection|null $conn */
    global $conn;

    // Ensure $conn is a PDO instance
    if (!$conn instanceof PDO) {
        die('Error: $conn is not a PDO instance.');
    }

    // Query to get user records
    $query = "SELECT * FROM users WHERE username = :username";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind the parameter
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Checks for matching user records
    if ($result) {
        // Return user records
        return $result;
    } else {
        // Return null if the record doesn't match
        return null;
    }
}

// Check user's role
function checkUserRole($requiredRole)
{
    if (isset($_SESSION['jenis_role']) && $_SESSION['jenis_role'] == $requiredRole) {
        // User has the required role
        return true;
    }
    return false; // Return false if the user doesn't have the required role
}

// Check Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = getUser($username);

    if ($user && password_verify($password, $user['password'])) {
        // Passwords match
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['jenis_role'] = $user['jenis_role'];
        $_SESSION['email'] = $user['email'];
        echo json_encode(['success' => true]);
    } else {
        // Invalid login
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['success' => false, 'message' => 'Login failed.']);
    }
}

// Close PDO Connection
if ($conn instanceof PDO) {
    $conn = null; // Close PDO connection
}
