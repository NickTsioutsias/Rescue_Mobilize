<?php

  //  PHP script for the Register form to insert rescuer submissions in the database 

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require "../database.php";

    // Create variables of submited items
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $carname = $_POST['car_name'];
    $latitude = $_POST['latitude'];
    $longtitude = $_POST['longtitude'];

      // Validate submitted items
      if(!preg_match("/^[a-zA-Z0-9_]{1,20}$/", $username)){
        header("Location: ../signup_rescuer.php?signup=wrongusername");
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z0-9!_]*$/", $password)){
        header("Location: ../signup_rescuer.php?signup=invalidpassword");
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z]*$/", $name)){
        header("Location: ../signup_rescuer.php?signup=invalidname");
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z]*$/", $lastname)){
        header("Location: ../signup_rescuer.php?signup=invalidlastname");
        exit();
      }    
      elseif(!preg_match("/^[a-zA-Z0-9]*$/", $carname)){
        header("Location: ../signup_rescuer.php?signup=invalidcarname");
        exit();
      }  
      elseif (!preg_match("/^-?((1[0-7]\d(\.\d{1,6})?)|([1-9]?\d(\.\d{1,6})?)|180(\.0{1,6})?)$/", $latitude)) {
        header("Location: ../signup_rescuer.php?signup=invalidlatitude");
        exit();
    } 
    elseif (!preg_match("/^-?((1[0-7]\d(\.\d{1,6})?)|([1-9]?\d(\.\d{1,6})?)|180(\.0{1,6})?)$/", $longtitude)) {
        header("Location: ../signup_rescuer.php?signup=invalidlongtitude");
        exit();
    }
      
      // Sanitization techniques: filtering malicious script
      $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
      $carname = filter_input(INPUT_POST, "car_name", FILTER_SANITIZE_SPECIAL_CHARS);
      $latitude = filter_input(INPUT_POST, "latitude", FILTER_SANITIZE_SPECIAL_CHARS);      
      $longtitude = filter_input(INPUT_POST, "longtitude", FILTER_SANITIZE_SPECIAL_CHARS);      
      
      // Checking for unique username
      $sql = "SELECT username FROM users WHERE username = ?";
      // Create prepared statement
      // Initialise connection with the database
      $stmt = mysqli_stmt_init($conn);
      // Prepare the statement using the $sql query
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup_rescuer.php?error=sqlerror");
        exit();
      } 
      else {
        // Bind the placeholder "?" parameters to the statement stmt 
        // s = string, i = integer, b = BLOB, d = double
        mysqli_stmt_bind_param($stmt, "s", $username);
        // Execute the statement inside the database
        mysqli_stmt_execute($stmt);
        // Store the result we got from the database and store it into the $stmt
        mysqli_stmt_store_result($stmt);
        // Check the Count of results (number of rows in the database) the $stmt has found
        $resultCheck = mysqli_stmt_num_rows($stmt);
        // If more than 0 usernames exist, username is not unique
        if ($resultCheck > 0) {
          header("Location: signup_rescuer.php?error=usernametaken");
          exit();
        }
      }
      
      // Create a rescuer user

      // Hash password for password integrity in the database
      $hash = password_hash($password, PASSWORD_DEFAULT);
      
      $role = "rescuer";

      // First insert values in users table
      // SQL query
      $sql = "INSERT INTO users (username, password, name, lastname, role) 
      VALUES (?, ?, ?, ?, ?);"; 

      // Create prepared statement
      // Initialise connection with the database
      $stmt = mysqli_stmt_init($conn);
      // Prepare the statement using the $sql query
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup_rescuer.php?error=sqlerror");
        exit();
      } else {
        // Bind the placeholder "?" parameters to the statement stmts 
        // s = string, i = integer, b = BLOB, d = double
        mysqli_stmt_bind_param($stmt, "sssss", $username, $hash, $name, $lastname, $role);
        // Execute the statement inside the database
        mysqli_stmt_execute($stmt);
      

        // Get the last inserted user_id
        $last_inserted_id = mysqli_insert_id($conn);

        // Last insert values in rescuer table with last inserted_id
        // SQL query
        $sql = "INSERT INTO rescuer (resc_id, r_cords, car_name) 
        VALUES (?, ST_GeomFromText(?), ?);";

        // Create prepared statement
        // Initialise connection with the database
        $stmt = mysqli_stmt_init($conn);
        // Prepare the statement using the $sql query
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../signup_rescuer.php?error=sqlerror");
          exit();
        } 
        else {
          // Create string look-alike of POINT datatype to get converted later in sql 
           $wktPoint = "POINT($longtitude $latitude)";
          // Bind the placeholder "?" parameters to the statement stmts 
          // s = string, i = integer, b = BLOB, d = double
          mysqli_stmt_bind_param($stmt, "iss", $last_inserted_id, $wktPoint, $carname);
          // Execute the statement inside the database
          mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);

        // // Get new rescuer data to a json file
        // require "rescuer_to_json.php";
      }
    
      header("Location: ../main_admin.php?signup=success");
      mysqli_close($conn);  
  }  
    
    
