<?php
  // In this file we convert Citizen data to a JSON file
  require "../database.php";

  // SQL prepared statement for selecting all users data with role = citizen
  $sql = "SELECT * FROM users WHERE role = 'citizen'";
  // Create prepared statement
  // Initialize connection with database
  $stmt = mysqli_stmt_init($conn);
  // Prepare the statement using the $sql query
  if (!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: citizen_to_json.php?error=sqlerror");
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
    file_put_contents('Citizens.json', $encoded_data);
  }
?>