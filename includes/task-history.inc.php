<?php

require "../database.php";
require "../config.php";
$user_id = $_SESSION['user_id'];

$sql = "SELECT 
task.task_id,
users.name AS user_name,
users.lastname AS user_lastname,
users.phone AS user_phone,
DATE_FORMAT(task.publish_date, '%Y-%m-%d %H:%i') AS publish_date,
inventory.name AS inventory_item_name,
task.quantity AS task_quantity
FROM
  task
INNER JOIN citizen ON task.citizen_id = citizen.citizen_id
INNER JOIN users ON citizen.citizen_id = users.users_id
INNER JOIN inventory ON task.id = inventory.id
WHERE task.complete = FALSE AND task.resc_id = ?;";
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

  // Write JSON data to a file
  file_put_contents('Active-tasks.json', $encoded_data);

  // Output JSON data
  echo $encoded_data;
}