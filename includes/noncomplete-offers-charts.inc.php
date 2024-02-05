<?php

require "../database.php";
require "../config.php";

// Select requests data
$sql = "SELECT DATE(publish_date) AS publish_date, 
COUNT(donate_id) AS offers
FROM task 
INNER JOIN donation ON task.task_id = donation.donate_id 
GROUP BY publish_date;";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'SQL error']);
  exit();
} else {
    mysqli_stmt_execute($stmt);
    // Set result as data gotten from SQL query
    $result = mysqli_stmt_get_result($stmt);
    // Fetch all rows from result as associative arrays
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Encode the formatted data into JSON
    $encoded_data = json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // Echo the JSON data
    echo $encoded_data;
}