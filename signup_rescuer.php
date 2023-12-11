<?php
  require "config.php";
  // Error Handling variable
  $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  // Role checking
  if($_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leaflet Map Example</title>
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
    <form action="includes/logout.php" method="post" id="login-form">
      <button type="submit" id="logout-button" name="logout-submit" style="width: 200px;">Logout</button>
    </form>
    <div class="login-container">
      <h2>Sign up!</h2>

      <form action="includes/register_rescuer.php" method="post" id="login-form">
        
      <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required>
        </div>

        <?php
            if(strpos($fullUrl, "signup=wrongusername") == true){
              echo '<p>Username needs to have alphanumeric characters and _ symbols only, up to 20 characters</p>';
            }
        ?>
      
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
      
        <?php
            if(strpos($fullUrl, "signup=invalidpassword") == true){
              echo '<p>Password needs to have alphanumeric characters and ! or _ symbols only</p>';
            }
        ?>

        <div class="form-group">
          <label>Name</label>
          <input type="text" id="name" name="name" required>
        </div>
        
        <?php
            if(strpos($fullUrl, "signup=invalidname") == true){
              echo '<p>Names can only be written in lower and uppercase letters</p>';
            }
        ?>

        <div class="form-group">
          <label>Lastname</label>
          <input type="text" id="lastname" name="lastname" required>
        </div>
        
        <?php
          if(strpos($fullUrl, "signup=invalidlastname") == true){
            echo '<p>Lastnames can only be written in lower and uppercase letters</p>';
          }
        ?>

        <div class="form-group">
          <label>Car name</label>
          <input type="text" id="car_name" name="car_name" required>
        </div>

        <?php
            if(strpos($fullUrl, "signup=invalidcarname") == true){
              echo '<p>Invalid car name</p>';
            }
        ?>
              
        <div class="form-group">
          <label for="latitude">Latitude:</label>
          <input type="text" id="latitude" name="latitude" required>
        </div>

        <?php
            if(strpos($fullUrl, "signup=invalidlatitude") == true){
              echo '<p>Latitude should be like: 38.246229</p>';
            }
        ?>
        
        <div> 
          <label for="longtitude">Longtitude:</label>
          <input type="text" id="longtitude" name="longtitude" required>
        </div>

        <?php
            if(strpos($fullUrl, "signup=invalidlongtitude") == true){
              echo '<p>Latitude should be like: 21.735412</p>';
            }
        ?>
        
        <button type="submit" name="signup-submit" id="submit-button">Register</button>
        
      </form>
      
      <p id="error-message" class="error-message"></p>
    
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

</body>
</html>
