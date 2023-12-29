<?php

  //  PHP script for the Register form to insert rescuer submissions in the database 

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require "../database.php";

    // Create variables of submited items
    $category = $_POST['category'];
    $item = $_POST['item'];

      // Validate submitted items
      if(!preg_match("/^[a-zA-Z0-9!_]*$/", $item)){
        echo json_encode(['success' => false, 'message' => 'Invalid item name']);
        exit();
    }
      
      // Sanitization techniques: filtering malicious script
      $item = filter_input(INPUT_POST, "item", FILTER_SANITIZE_SPECIAL_CHARS);
      
      // Checking for unique item
      $sql = "SELECT name FROM inventory WHERE name = ?";
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
        mysqli_stmt_bind_param($stmt, "s", $item);
        // Execute the statement inside the database
        mysqli_stmt_execute($stmt);
        // Store the result we got from the database and store it into the $stmt
        mysqli_stmt_store_result($stmt);
        // Check the Count of results (number of rows in the database) the $stmt has found
        $resultCheck = mysqli_stmt_num_rows($stmt);
        // If more than 0 usernames exist, username is not unique
        if ($resultCheck > 0) {
          echo json_encode(['success' => false, 'message' => 'Item name taken']);
          exit();
        }
      }
      
      // Create item

      $sql = "SELECT * FROM category where categ_name = ?";
      $stmt = mysqli_stmt_init($conn); 
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['success' => false, 'message' => 'There was an SQL error']);
        exit();
      }
      else{
        mysqli_stmt_bind_param($stmt, "s", $category);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $sql = "INSERT INTO inventory (name, categ_id) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          echo json_encode(['success' => false, 'message' => 'There was an SQL error']);
          exit();
        }
        else{
          mysqli_stmt_bind_param($stmt, "si", $item, $row['categ_id']);
          mysqli_stmt_execute($stmt);

          $sql = "SELECT * FROM inventory";
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
            file_put_contents('Items.json', $encoded_data);
            echo json_encode(['success' => true, 'message' => 'Success', 'redirect' => 'main_admin.php']);
          }
        }
        mysqli_close($conn);  
      }
    }