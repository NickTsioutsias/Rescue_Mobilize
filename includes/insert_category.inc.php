<?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    require "../database.php";

    // Create variables of submitted items
    $category = $_POST['category'];
    
     // Validate inputs on the server side
     if (empty($category)) {
      echo json_encode(['success' => false, 'message' => 'Empty fields']);
      exit();
    }
    if(!preg_match("/^[a-zA-Z0-9_.\s]{1,20}$/", $category)){
      echo json_encode(['success' => false, 'message' => 'Invalid category name']);
      exit();
    }  
    
    // Sanitization techniques: filtering malicious script
    $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
  
    // Checking for unique category
    $sql = "SELECT * FROM category WHERE categ_name = ?";
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
      mysqli_stmt_bind_param($stmt, "s", $category);
      // Execute the statement inside the database
      mysqli_stmt_execute($stmt);
      // Store the result we got from the database and store it into the $stmt
      mysqli_stmt_store_result($stmt);
      // Check the Count of results (number of rows in the database) the $stmt has found
      $resultCheck = mysqli_stmt_num_rows($stmt);
      // If more than 0 usernames exist, username is not unique
      if ($resultCheck > 0) {
        echo json_encode(['success' => false, 'message' => 'This category already exists']);
        exit();
      }
    }

    // Create category
    // Insert submitted values into category table
    $sql = "INSERT INTO category (categ_name) VALUES(?)";

    // Create prepared statement
    // Initialise connection with the database
    $stmt = mysqli_stmt_init($conn);
    // Prepare the statement using the $sql query
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo json_encode(['success' => false, 'message' => 'There was an SQL error']);
      exit();
    } else {
      // Bind the placeholder "?" parameters to the statement stmts 
      // s = string, i = integer, b = BLOB, d = double
      mysqli_stmt_bind_param($stmt, "s", $category);
      // Execute the statement inside the database
      mysqli_stmt_execute($stmt);
    }  
  require "../database.php";

  $sql = "SELECT * FROM category";
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
    file_put_contents('Categories.json', $encoded_data);
  }

    echo json_encode(['success' => true, 'message' => 'Success', 'redirect' => 'main_admin.php']);
    exit();
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn); 
  }

