<?php 

//  PHP script for the Register form to insert rescuer submissions in the database 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  require "../database.php";

  // Create variables of submited items
  $item = $_POST['item'];
  $quantity = $_POST['quantity'];

    // Validate submitted items
    $jsonData = file_get_contents('Items.json');

    $data = json_decode($jsonData, true);

    // Check if decoding was successful
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
      // Handle decoding error
      echo json_encode(['success' => false, 'message' => 'json file not found']);
      exit();
    } 
    elseif (in_array($item, array_column($data, 'name')) === false) {
      echo json_encode(['success' => false, 'message' => 'item is not in the database']);
      exit();
    }
    else{
      $quantity = filter_input(INPUT_POST, "quantity", FILTER_SANITIZE_SPECIAL_CHARS);      
      $item = filter_input(INPUT_POST, "item", FILTER_SANITIZE_SPECIAL_CHARS); 

      // Change quantity of item
      $sql = "UPDATE inventory SET quantity = quantity + ? WHERE name = ?;"; 

      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'SQL error']);
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "is", $quantity, $item);
        mysqli_stmt_execute($stmt);
        } 
    
      header('Content-Type: application/json');
      echo json_encode(['success' => true, 'message' => 'Update success', 'redirect' => 'main_admin.php']);
      exit();

    }
    }
    

    mysqli_close($conn);  
 
?>