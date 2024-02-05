<?php
require "../database.php";
require "../config.php";

$sql = "SELECT car_inv.resc_id, rescuer.car_name, inventory.name, car_inv.quantity FROM car_inv 
INNER JOIN inventory ON car_inv.id = inventory.id
INNER JOIN rescuer ON car_inv.resc_id = rescuer.resc_id;";

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
    file_put_contents('Car_inv.json', $encoded_data);
    
    // Echo the JSON data
    echo $encoded_data;
  }
?>