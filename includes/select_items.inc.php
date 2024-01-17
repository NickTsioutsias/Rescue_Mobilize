<?php
require "../database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_POST['selectedText'])) {
  echo json_encode(['success' => false, 'message' => 'selectedText not set']);
  exit();
}


$selectedText = $_POST['selectedText'];

$sql = "SELECT inventory.name 
FROM inventory
INNER JOIN category ON inventory.categ_id = category.categ_id
WHERE category.categ_name = ?;";
    // Create prepared statement
    // Initialise connection with the database
    $stmt = mysqli_stmt_init($conn);
    // Prepare the statement using the $sql query
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo json_encode(['success' => false, 'message' => 'There was an SQL error']);
      exit();
    } 
    else {
      // Bind the placeholder "?" parameters to the statement stmt 
      // s = string, i = integer, b = BLOB, d = double
      mysqli_stmt_bind_param($stmt, "s", $selectedText);
      // Execute the statement inside the database
      mysqli_stmt_execute($stmt);

      // Set result as data gotten from sql query
      $result = mysqli_stmt_get_result($stmt);
      // Fetch all rows from result as associative arrays
      $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
      // Encode all the rows into JSON data
      $encoded_data = json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

      echo $encoded_data;
      exit();


    }