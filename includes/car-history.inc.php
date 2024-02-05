<?php

require "../database.php";
require "../config.php";
$user_id = $_SESSION['user_id'];

$sql = "SELECT 
inventory.name AS inventory_item_name,
car_inv.quantity AS quantity
FROM
  car_inv
INNER JOIN inventory ON car_inv.id = inventory.id
WHERE car_inv.resc_id = ?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  echo json_encode(['success' => false, 'message' => 'SQL error']);
}
else {
  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  // Set result as data gotten from sql query
  $result = mysqli_stmt_get_result($stmt);
  // Fetch all rows from result as associative arrays
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

  // Encode the modified $rows array to JSON
  $encoded_data = json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

  // Output JSON data
  echo $encoded_data;
}