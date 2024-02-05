<?php 


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  require "../database.php";
  require "../config.php";

  // Create variables of submited items
  $user_id = $_SESSION['user_id'];
  $item = $_POST['item'];
  $quantity = $_POST['quantity'];

  $quantity = filter_input(INPUT_POST, "quantity", FILTER_SANITIZE_SPECIAL_CHARS);      
      $item = filter_input(INPUT_POST, "item", FILTER_SANITIZE_SPECIAL_CHARS); 

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

// Get id of car_inv table
$sql = "SELECT id FROM inventory WHERE BINARY name = ?;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'SQL error']);
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "s", $item);
        mysqli_stmt_execute($stmt);

        // Bind the result variable
        mysqli_stmt_bind_result($stmt, $resultId);

        // Fetch the result
        mysqli_stmt_fetch($stmt);

  // Check if item exists in the car inventory
  $sql = "SELECT id FROM car_inv WHERE id = ?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'SQL error']);
      exit();
  } else {
      mysqli_stmt_bind_param($stmt, "i", $resultId);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);

      // Check the Count of results (number of rows in the database) the $stmt has found
      $resultCheck = mysqli_stmt_num_rows($stmt);

      // If more than 0 items exist, fetch the item_id
      if ($resultCheck == 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Item does not exist in the car inventory']);
        exit();
      }
    }


        
      // Check if item quantity is enough in the car inventory
      $sql = "SELECT quantity FROM car_inv WHERE id = ?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'SQL error']);
      exit();
  } 
  else {
      mysqli_stmt_bind_param($stmt, "i", $resultId);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $database_quantity);

      mysqli_stmt_fetch($stmt);
      
      // Check if the requested quantity is greater than the database quantity
      if ($quantity > $database_quantity) {
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds the available quantity in the database. Max quantity is ' . $database_quantity]);
          exit();
      } 



    // Change quantity of item in car inventory 
    $sql = "UPDATE car_inv SET quantity = quantity - ? WHERE id = ?;"; 

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'SQL error']);
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "is", $quantity, $resultId);
      mysqli_stmt_execute($stmt);
      } 

      // Change quantity of item in base inventory
    $sql = "UPDATE inventory SET quantity = quantity + ? WHERE BINARY name = ?;"; 

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'SQL error']);
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "is", $quantity, $item);
      mysqli_stmt_execute($stmt);
      header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => 'Successful unload' . $database_quantity]);
          exit();
      } 
    }
  }
  }

    mysqli_close($conn);  
 
?>