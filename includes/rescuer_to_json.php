<?php
  // In this file we convert Rescuer data to a JSON file
  require "../database.php";

  // SQL prepared statement for selecting all users data with role = rescuer
  $sql = "SELECT * FROM users WHERE role = 'rescuer'";
  // Create prepared statement
  // Initialize connection with database
  $stmt = mysqli_stmt_init($conn);
  // Prepare the statement using the $sql query
  if (!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: rescuer_to_json.php?error=sqlerror");
    exit();
  }
  else{
    mysqli_stmt_execute($stmt);
    // Set result as data gotten from sql query
    $result = mysqli_stmt_get_result($stmt);
    // Fetch all rows from result as associative arrays
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // Encode all the rows into JSON data
    $encoded_data = json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('Rescuers.json', $encoded_data);
  }
?>