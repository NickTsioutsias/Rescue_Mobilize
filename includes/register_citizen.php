<?php

  //  PHP script for the Register form to insert citizen submissions in the database 

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require "../database.php";

    // Create variables of submited items
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $zip = $_POST['zip'];
    $city = $_POST['city'];
    $country = $_POST['country'];

    // Check if any submition was empty 
    if (empty($username) || empty($password) || empty($name) || empty($lastname) || empty($phone) 
      || empty($email) || empty($address) || empty($zip) || empty($latitude) || empty($longitude)) {

        header("Location: ../index.php?signup=empty");
        exit();
    }
    else {
      // Validate submitted items
      if(preg_match("/^[a-zA-Z0-9_.\s]{1,20}*$/", $username)){
        header("Location: ../index.php?signup=wrongusername");
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z0-9!_]*$/", $password)){
        header("Location: ../index.php?signup=invalidpassword");
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z.\s]*$/", $name)){
        header("Location: ../index.php?signup=invalidname");
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z.\s]*$/", $lastname)){
        header("Location: ../index.php?signup=invalidlastname");
        exit();
      }    
      elseif(preg_match("/^[0-9]{10}*$/", $phone)){
        header("Location: ../index.php?signup=invalidphone");
        exit();
      } 
      elseif(!preg_match("/^[a-zA-Z\s,.'0-9]*$/", $address)){
        header("Location: ../index.php?signup=invalidaddress");
        exit();
      } 
      elseif(preg_match("/^[0-9]{5}*$/", $zip)){
        header("Location: ../index.php?signup=invalidzip");
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z\s]*$/", $country)){
        header("Location: ../index.php?signup=invalidlastname");
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z\s]*$/", $city)){
        header("Location: ../index.php?signup=invalidlastname");
        exit();
      }
      
      
      // Sanitization techniques: filtering malicious script
      $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
      $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
      $address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_SPECIAL_CHARS);
      $zip = filter_input(INPUT_POST, "zip", FILTER_SANITIZE_SPECIAL_CHARS);      
      $city = filter_input(INPUT_POST, "city", FILTER_SANITIZE_SPECIAL_CHARS);      
      $country = filter_input(INPUT_POST, "country", FILTER_SANITIZE_SPECIAL_CHARS);      
            
      // Checking for unique username and phone
      $sql = "SELECT username, phone FROM users WHERE username = ?, ?";
      // Create prepared statement
      // Initialise connection with the database
      $stmt = mysqli_stmt_init($conn);
      // Prepare the statement using the $sql query
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
      } 
      else {
        // Bind the placeholder "?" parameters to the statement stmt 
        // s = string, i = integer, b = BLOB, d = double
        mysqli_stmt_bind_param($stmt, "ss", $username, $phone);
        // Execute the statement inside the database
        mysqli_stmt_execute($stmt);
        // Store the result we got from the database and store it into the $stmt
        mysqli_stmt_store_result($stmt);
        // Check the Count of results (number of rows in the database) the $stmt has found
        $resultCheck = mysqli_stmt_num_rows($stmt);
        // If more than 0 usernames exist, username is not unique
        if ($resultCheck > 0) {
          header("Location: register_citizen.php?error=usernamephonetaken");
          exit();
        }
      }


      
      // Create a citizen user

      // Hash password for password integrity in the database
      $hash = password_hash($password, PASSWORD_DEFAULT);
      
      // Set role citizen
      $role = "citizen";

      // First insert values in users table
      // SQL query
      $sql = "INSERT INTO users (username, password, name, lastname, phone, country, city ,address, zip, role) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"; 

      // Create prepared statement
      // Initialise connection with the database
      $stmt = mysqli_stmt_init($conn);
      // Prepare the statement using the $sql query
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
      } else {
        // Bind the placeholder "?" parameters to the statement stmts 
        // s = string, i = integer, b = BLOB, d = double
        mysqli_stmt_bind_param($stmt, "ssssssssss", $username, $hash, $name, $lastname, $phone, $country, $city, $address, $zip, $role);
        // Execute the statement inside the database
        mysqli_stmt_execute($stmt);
      }

      // Get the last inserted user_id
      $last_inserted_id = mysqli_insert_id($conn);

      // Last insert values in citizen table with last inserted_id
      // SQL query
      $sql = " INSERT INTO citizen (citizen_id) 
      VALUES (?);";

      // Create prepared statement
      // Initialise connection with the database
      $stmt = mysqli_stmt_init($conn);
      // Prepare the statement using the $sql query
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
      } else {
        // Bind the placeholder "?" parameters to the statement stmts 
        // s = string, i = integer, b = BLOB, d = double
        mysqli_stmt_bind_param($stmt, "i", $last_inserted_id);
        // Execute the statement inside the database
        mysqli_stmt_execute($stmt);
      }
      

    }

    header("Location: ../index.php?signup=success");
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);  
  }
    
    
