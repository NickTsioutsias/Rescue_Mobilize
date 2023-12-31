<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../database.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

  // Validate inputs on the server side
  if (empty($username) || empty($password)) {
    echo 'Please enter both username and password.';
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
        header("Location: ../rescuerlogin.php'?error=sqlerror");
        exit();
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
        if ($row = mysqli_fetch_assoc($result)) {
          // Match hashed password from database to password from submission 
          if ($password != $row['password']) {
            header("Location: ../rescuerlogin.php?error=wrongpwd");
            exit();
          }
          elseif($password == $row['password']) {
            session_start();
            $_SESSION['user_id'] = $row['users_id'];
            $_SESSION['role'] = $row['role'];

            // Redirect to index.php and give success message
            header("Location: ../main_rescuer.php?login=success");
            exit();
          }
        }
        else {
          header("Location: ../rescuerlogin.php?error=norescueruser");
          exit();
        }
      }

  }
  else {
    header("Location: ../rescuerlogin.php");
    exit();
}