<?php

require "../database.php";
require "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Create variables of submited items
  $item = $_POST['item'];
  $quantity = $_POST['quantity'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $user_id = $_SESSION['user_id'];


  // Validate
  if (!preg_match("/^-?((1[0-7]\d(\.\d{1,6})?)|([1-9]?\d(\.\d{1,6})?)|180(\.0{1,6})?)$/", $latitude)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Click the map to get your coordinates']);
    exit();
  } 
  elseif (!preg_match("/^-?((1[0-7]\d(\.\d{1,6})?)|([1-9]?\d(\.\d{1,6})?)|180(\.0{1,6})?)$/", $longitude)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Click the map to get your coordinates']);
    exit();
  }

  // Sanitize
  $item = filter_input(INPUT_POST, "item", FILTER_SANITIZE_SPECIAL_CHARS);
  $quantity = filter_input(INPUT_POST, "quantity", FILTER_SANITIZE_SPECIAL_CHARS);
  $latitude = filter_input(INPUT_POST, "latitude", FILTER_SANITIZE_SPECIAL_CHARS);
  $longitude = filter_input(INPUT_POST, "longitude", FILTER_SANITIZE_SPECIAL_CHARS);

  // Debugging
// header('Content-Type: application/json');
// echo json_encode(['received_data' => $_POST]);
// exit();

  // Check if item exists in the database
$sql = "SELECT id FROM inventory WHERE BINARY name = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'SQL error']);
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $item);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // Check the Count of results (number of rows in the database) the $stmt has found
    $resultCheck = mysqli_stmt_num_rows($stmt);

    // If more than 0 items exist, fetch the item_id
    if ($resultCheck > 0) {
        // Fetch the result
        mysqli_stmt_bind_result($stmt, $item_id);
        mysqli_stmt_fetch($stmt);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Item does not exist in the database']);
        exit();
    }
}

  // Debugging
  // header('Content-Type: application/json');
  // echo json_encode(['data' => $item_id]);
  // exit();


  // Create request
  // First insert values into task table
  $sql = "INSERT INTO task (citizen_id, quantity, id, publish_date) 
  VALUES (?, ?, ?, NOW());"; 
  // Create prepared statement
  // Initialise connection with the database
  $stmt = mysqli_stmt_init($conn);
  // Prepare the statement using the $sql query
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'SQL error']);
    exit();
  } 
  else{
    // Create string look-alike of POINT datatype to get converted later in sql 
    // Bind the placeholder "?" parameters to the statement stmts 
    // s = string, i = integer, b = BLOB, d = double
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $quantity, $item_id);
    // Execute the statement inside the database
    mysqli_stmt_execute($stmt);
    
    // Get the last inserted task_id
    $last_inserted_id = mysqli_insert_id($conn);
  

    // Last insert values in request table with last inserted_id
    // SQL query
    $sql = "INSERT INTO request (request_id, location) 
    VALUES (?, ST_GeomFromText(?));"; 
    $wktPoint = "POINT($longitude $latitude)";
    // Create prepared statement
    // Initialise connection with the database
    $stmt = mysqli_stmt_init($conn);
    // Prepare the statement using the $sql query
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'SQL error']);
      exit();
    } else {
      
      // Bind the placeholder "?" parameters to the statement stmts 
      // s = string, i = integer, b = BLOB, d = double
      mysqli_stmt_bind_param($stmt, "is", $last_inserted_id, $wktPoint);
      // Execute the statement inside the database
      mysqli_stmt_execute($stmt);
      echo json_encode(['success' => true, 'message' => 'Signup success', 'redirect' => 'main_citizen.php', 
      'user_id' => $user_id, 'item_id' => $item_id, 'quantity' => $quantity, 'wktPoint' => $wktPoint]);
    }
    mysqli_close($conn);  
    mysqli_stmt_close($stmt);
  }
}