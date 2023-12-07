<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../database.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // If username or password not submitted get redirected to index.php
    if (empty($username) || empty($password)) {
      header("Location: ../index.php?login=empty");
      exit();
    }
    else {
      // Select username which corresponds to the submitted username
      $sql = "SELECT * FROM users WHERE username = ?;";
      // Create prepared statement
      // Initialise connection with the database
      $stmt = mysqli_stmt_init($conn);
      // Prepare the statement using the $sql query
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php'?error=sqlerror");
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
          $pwdCheck = password_verify($password, $row['password']);
          if ($pwdCheck == false) {
            header("Location: ../index.php?error=wrongpwd");
            exit();
          }
          elseif($pwdCheck == true) {
            session_start();
            $_SESSION['user_id'] = $row['users_id'];
            $_SESSION['role'] = $row['role'];

            // Redirect to index.php and give success message
            header("Location: ../main.php?login=success");
            exit();
          }
        }
        else {
          header("Location: ../index.php?error=nouser");
          exit();
        }
      }
    }

  }
  else {
    header("Location: ../index.php");
    exit();
}