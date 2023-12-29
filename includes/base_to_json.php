<?php
require "../database.php";
 $sql = "SELECT ST_X(base_cords) as lng, ST_Y(base_cords) as lat FROM base;";
 // Create prepared statement
 // Initialize connection with database
 $stmt = mysqli_stmt_init($conn);
 // Prepare the statement using the $sql query
 if (!mysqli_stmt_prepare($stmt, $sql)){
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
  //  file_put_contents('Base.json', $encoded_data);
  echo $encoded_data;
}