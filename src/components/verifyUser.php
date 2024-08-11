<?php

include('../database/pgconn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /** @var \PgSql\Connection|null $conn */
    global $conn;

    // Ensure $conn is a PDO instance
    if (!$conn instanceof PDO) {
        die('Error: $conn is not a PDO instance.');
    }

    if (isset($_POST['token'])) {
        $token = pg_escape_string($_POST['token']);

        $query = "SELECT * FROM users WHERE verification_code = $1 AND verified = false";
        $stmt = pg_prepare($conn, "", $query);

        $result = pg_execute($conn, "", array($token));

        if ($result !== false) {
            // Check if the query execution was successful

            if (pg_num_rows($result) > 0) {
                // Fetch the user data
                $user = pg_fetch_assoc($result);
                $userId = $user['id'];

                // Update the verified status
                $updateQuery = "UPDATE users SET verified = true WHERE id = $1";
                $updateStmt = pg_prepare($conn, "", $updateQuery);

                // Execute the prepared statement with the user ID parameter
                pg_execute($conn, "", array($userId));

                echo json_encode(['success' => true, 'message' => 'Email verification successful! You can now log in.']);
            } else {
                // Handle the case where no rows were returned
                echo json_encode(['success' => false, 'message' => 'Invalid or expired verification token.']);
            }
        } else {
            // Handle the case where the query execution failed
            echo json_encode(['success' => false, 'message' => 'Error executing the query.']);
        }
    } else {
        // Send a JSON-encoded response for the case where 'token' is not set in the POST data
        echo json_encode(['success' => false, 'message' => 'Invalid request. Token not provided.']);
    }
}

// Close PDO Connection
if ($conn instanceof PDO) {
    $conn = null; // Close PDO connection
}
