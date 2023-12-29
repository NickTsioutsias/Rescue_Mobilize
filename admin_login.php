<?php
session_start();

require "database.php";

// Check login credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs on the server side
    if (empty($username) || empty($password)) {
      echo json_encode(['success' => false, 'message' => 'Empty fields']);
    } 
    
    // Sanitize inputs
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    // Select username which corresponds to the submitted username
    $sql = "SELECT * FROM users WHERE username = ?;";
    // Create prepared statement
    // Initialise connection with the database
    $stmt = mysqli_stmt_init($conn);
    // Prepare the statement using the $sql query
    if (!mysqli_stmt_prepare($stmt, $sql)) {
       echo json_encode(['success' => false, 'message' => 'SQL error']);
    }
    else {
      // Bind the placeholder "?" parameters to the statement stmt 
      // s = string, i = integer, b = BLOB, d = double
      mysqli_stmt_bind_param($stmt, "s", $username);
      // Execute the statement inside the database
      mysqli_stmt_execute($stmt);
      // Set result as data gotten from sql query
      $result = mysqli_stmt_get_result($stmt);
      // Make the data into associative array where column names are used as keys and has the values of $result
      if($row = mysqli_fetch_assoc($result)){
        // Match password from the database to the password from submission
        if($password != $row['password']){
            echo json_encode(['success' => false, 'message' => 'Wrong password']);
        }
        else{ 
            // Passwords match
            $_SESSION['user_id'] = $row['users_id'];
            $_SESSION['role'] = $row['role'];
            echo json_encode(['success' => true, 'message' => 'Login successful']);
        }
    } 
    else{
        // Username not found, or other authentication failed
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
    }
  }
  $stmt->close();
  $conn->close();
?>
