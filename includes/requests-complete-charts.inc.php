<?php

require "../database.php";
require "../config.php";

// Select requests data
$sql = "SELECT DATE(complete_date), 
COUNT(*) AS completed_tasks
FROM task 
INNER JOIN request ON task.task_id = request.request_id 
GROUP BY complete_date;";
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
