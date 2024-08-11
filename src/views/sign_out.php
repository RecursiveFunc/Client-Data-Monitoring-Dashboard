<?php
// Start the session
session_start();

// Clear the session data
session_unset();
session_destroy();

// Redirect to the login page
header('Location: sign_in.php');
exit();
