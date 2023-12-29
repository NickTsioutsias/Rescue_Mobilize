<?php
  require "config.php";
  // Error Handling variable
  $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  // Role checking
  if($_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
  }

  //  PHP script for the Register form to insert rescuer submissions in the database 

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require "database.php";

    // Create variables of submited items
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $car_name = $_POST['car_name'];
    $latitude = $_POST['latitude'];
    $longtitude = $_POST['longtitude'];

      // Validate submitted items
      if(!preg_match("/^[a-zA-Z0-9_.\s]{1,20}$/", $username)){
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Username needs to have alphanumeric characters and _ symbols only, up to 20 characters']);
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z0-9!_]*$/", $password)){
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Password needs to have alphanumeric characters and ! or _ symbols only']);
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z.\s]*$/", $name)){
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Names can only be written in lower and uppercase letters']);
        exit();
      }
      elseif(!preg_match("/^[a-zA-Z.\s]*$/", $lastname)){
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Lastnames can only be written in lower and uppercase letters']);
        exit();
      }    
      elseif(!preg_match("/^[a-zA-Z0-9]*$/", $car_name)){
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid car name']);
        exit();
      }  
      elseif (!preg_match("/^-?((1[0-7]\d(\.\d{1,6})?)|([1-9]?\d(\.\d{1,6})?)|180(\.0{1,6})?)$/", $latitude)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Latitude should be like: 38.246229']);
        exit();
    } 
    elseif (!preg_match("/^-?((1[0-7]\d(\.\d{1,6})?)|([1-9]?\d(\.\d{1,6})?)|180(\.0{1,6})?)$/", $longtitude)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Longtitude should be like: 21.735412']);
      exit();
    }
      
      // Sanitization techniques: filtering malicious script
      $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
      $car_name = filter_input(INPUT_POST, "car_name", FILTER_SANITIZE_SPECIAL_CHARS);
      $latitude = filter_input(INPUT_POST, "latitude", FILTER_SANITIZE_SPECIAL_CHARS);      
      $longtitude = filter_input(INPUT_POST, "longtitude", FILTER_SANITIZE_SPECIAL_CHARS);      
      
      // Checking for unique username
      $sql = "SELECT username FROM users WHERE username = ?";
      // Create prepared statement
      // Initialise connection with the database
      $stmt = mysqli_stmt_init($conn);
      // Prepare the statement using the $sql query
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'SQL error']);
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
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => 'Username is taken']);
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
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'SQL error']);
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
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => 'SQL error']);
          exit();
        } 
        else {
          // Create string look-alike of POINT datatype to get converted later in sql 
           $wktPoint = "POINT($longtitude $latitude)";
          // Bind the placeholder "?" parameters to the statement stmts 
          // s = string, i = integer, b = BLOB, d = double
          mysqli_stmt_bind_param($stmt, "iss", $last_inserted_id, $wktPoint, $car_name);
          // Execute the statement inside the database
          mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);

      }
    
      header('Content-Type: application/json');
      echo json_encode(['success' => true, 'message' => 'Signup success', 'redirect' => 'main_admin.php']);
      exit();
      mysqli_close($conn);  
  }  
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create rescuer account</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
     <!-- Make sure you put this AFTER Leaflet's CSS -->
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
      crossorigin="">
  </script>
  <link rel="stylesheet" href="signup_rescuer.css">
  <style>
    p{
      color: red;
    }
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: space-between;
      margin: 0;
      height: 100vh; /* Make sure the body takes the full viewport height */
    }

    #form-container {
      /* form should take a third of the page */
      width: 30%;
      padding: 20px;
    }

    #map-container {
      /* map should take rest of page */
      flex: 1;
    }

    #map {
      height: 100%;
      width: 100%;
    }
  </style>
</head>
<body>

  <div id="form-container">
    <form id="logout-form">
      <button type="submit" id="logout-button" name="logout-submit" style="width: 200px;">Logout</button>
    </form>
    <div class="login-container">
      <h2>Sign up!</h2>

      <form id="rescuer-form">
        
      <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
      </div>
      
      <div class="form-group">
          <label>Name</label>
          <input type="text" id="name" name="name" required>
      </div>
        
      <div class="form-group">
          <label>Lastname</label>
          <input type="text" id="lastname" name="lastname" required>
      </div>
        
      <div class="form-group">
          <label>Car name</label>
          <input type="text" id="car_name" name="car_name" required>
      </div>


      <div class="form-group">
          <label for="latitude">Latitude:</label>
          <input type="text" id="latitude" name="latitude" required>
      </div>

      <div class="form-group"> 
          <label for="longtitude">Longtitude:</label>
          <input type="text" id="longtitude" name="longtitude" required>
      </div>

      <button type="submit" name="signup-submit" id="submit-button">Register</button>
        
      </form>
        
      <p id="message" class="error-message"></p>
      
    </div>
  
  </div>
  
  <div id="map-container">
    <div id="map"></div>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin="">
  </script>

  <script>
    var map = L.map('map').setView([38.2463673403233,21.735140649945635], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Make marker location
    var marker = L.marker([38.2463673403233, 21.735140649945635]).addTo(map);

    // Make marker popup
    marker.bindPopup("Πλατεία Γεωργίου.").openPopup();

    // Add click event listener to the map
    map.on('click', function (e) {
      var coordinates = e.latlng;
      var formattedLatitude = coordinates.lat.toFixed(6);
      var formattedLongtitude = coordinates.lng.toFixed(6);
      // Update the input field with the formatted latitude
      document.getElementById('latitude').value = formattedLatitude;
      // Update the input field with the formatted longtitude
      document.getElementById('longtitude').value = formattedLongtitude;
    });
  </script>

  <script src="signup_rescuer.js"></script>

</body>
</html>


  