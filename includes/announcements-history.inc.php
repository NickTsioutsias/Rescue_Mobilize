<?php
  require "../database.php";

  // Create announcement
  $sql = "SELECT description, quantity, categ_name, item_name FROM announcements
  ORDER BY announ_id DESC;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(['success' => false, 'message' => 'There was an SQL error']);
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
  }

  echo $encoded_data;
  mysqli_close($conn);