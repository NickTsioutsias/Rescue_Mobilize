<?php

session_start();

// Deletes all the set session variables
session_unset();

// Stop session
session_destroy();

// Redirect back to index.php page
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'successful logout' ,'redirect' => 'login.html']);