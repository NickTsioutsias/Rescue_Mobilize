<?php
  // In this file we convert Rescuer data to a JSON file
  require "../database.php";

  $sql = "SELECT rescuer.resc_id, rescuer.curr_task, ST_X(rescuer.r_cords) as lng, ST_Y(rescuer.r_cords) as lat, 
  rescuer.car_name, car_inv.quantity, inventory.name FROM rescuer 
  LEFT JOIN car_inv ON rescuer.resc_id = car_inv.resc_id 
  LEFT JOIN inventory ON car_inv.id = inventory.id";
  
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(['success' => false, 'message' => 'SQL error']);
  } else {
    mysqli_stmt_execute($stmt);
    // Set result as data gotten from SQL query
    $result = mysqli_stmt_get_result($stmt);
    // Fetch all rows from result as associative arrays
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Convert rows to an associative array with resc_id as keys
    $formattedData = [];
    foreach ($rows as $row) {
      $formattedData[$row['resc_id']][] = $row;
    }

    // Encode the formatted data into JSON
    $encoded_data = json_encode($formattedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // Save the JSON data to a file
    file_put_contents('Rescuers.json', $encoded_data);
    
    // Echo the JSON data
    echo $encoded_data;
  }
?>
