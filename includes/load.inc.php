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
        
      // Check if item exists in the database
      $sql = "SELECT quantity FROM inventory WHERE BINARY name = ?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'SQL error']);
      exit();
  } 
  else {
      mysqli_stmt_bind_param($stmt, "s", $item);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $database_quantity);

      mysqli_stmt_fetch($stmt);
      
      // Check if the requested quantity is greater than the database quantity
      if ($quantity > $database_quantity) {
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds the available quantity in the database. Max quantity is ' . $database_quantity]);
          exit();
      } 
      
    
    // Change quantity of item
    $sql = "UPDATE inventory SET quantity = quantity - ? WHERE BINARY name = ?;"; 

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'SQL error']);
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "is", $quantity, $item);
      mysqli_stmt_execute($stmt);
      } 

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

      

    
  $sql = "SELECT resc_id, id FROM car_inv 
  WHERE resc_id = ? AND id = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'SQL error']);
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $resultId);
    mysqli_stmt_execute($stmt);

    // Store the result set
    mysqli_stmt_store_result($stmt);

    // Get the number of rows returned
    $rowCount = mysqli_stmt_num_rows($stmt);

    if ($rowCount > 0) {
      $sql = "UPDATE car_inv SET quantity = quantity + ? WHERE resc_id = ? AND id = ?;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'SQL error']);
        exit();
      } 
      else {
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $user_id, $resultId);
        mysqli_stmt_execute($stmt);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Update success']);
        exit();
      }
    }
    elseif($rowCount == 0){
      $sql = "INSERT INTO car_inv (resc_id, id, quantity) VALUES (?, ?, ?);;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'SQL error']);
        exit();
      } 
      else {
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $resultId, $quantity);
        mysqli_stmt_execute($stmt);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Update success']);
        exit();
      }
    }

  }

    }


      
    
      

    }
  }

    mysqli_close($conn);  
 
?>