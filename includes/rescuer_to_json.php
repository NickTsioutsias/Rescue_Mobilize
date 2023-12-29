<?php
  // In this file we convert Rescuer data to a JSON file
  require "../database.php";

  $sql = "SELECT resc_id, curr_task, ST_X(r_cords) as lng, ST_Y(r_cords) as lat, car_name FROM rescuer;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(['success' => false, 'message' => 'SQL error']);
 }
 else {
    mysqli_stmt_execute($stmt);
    // Set result as data gotten from sql query
    $result = mysqli_stmt_get_result($stmt);
    // Fetch all rows from result as associative arrays
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // Encode all the rows into JSON data
    $encoded_data = json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('Rescuers.json', $encoded_data);
    echo $encoded_data;
 }
