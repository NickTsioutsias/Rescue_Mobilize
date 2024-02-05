<?php
  require "../database.php";
  require "../config.php";

  $user_id = $_SESSION['user_id'];
  // Create announcement
  $sql = "SELECT task.complete, task.task_id, inventory.name, task.quantity, task.publish_date FROM task 
  INNER JOIN inventory ON task.id = inventory.id 
  INNER JOIN donation ON task.task_id = donation.donate_id 
  WHERE citizen_id = ? AND task_id = donate_id 
  ORDER BY task_id DESC;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(['success' => false, 'message' => 'There was an SQL error']);
    exit();
  }
  else{
    mysqli_stmt_bind_param($stmt, "i", $user_id);
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