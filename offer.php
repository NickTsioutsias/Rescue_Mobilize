<?php
  require "config.php";
  if($_SESSION['role'] != 'citizen'){
    header("Location: login.html");
    exit();
  }

  if (isset($_GET['announcement'])) {
    $announcementData = json_decode($_GET['announcement'], true);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
     <!-- Make sure you put this AFTER Leaflet's CSS -->
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
      crossorigin="">
  </script>
<style>
      body {
      font-family: Arial, sans-serif;
      display: flex;
      /* containers should be in rows */
      flex-direction: column;
      justify-content: flex-start;
      margin: 0;
      padding: 0;
      height: 100vh; /* Make sure the body takes the full viewport height */
    }
    input[type="text"],
    input[type="password"] {
      width: calc(100% - 10px);
      padding: 5px;
      border-radius: 3px;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      padding: 8px 10px;
      border: none;
      border-radius: 3px;
      background-color: #007bff;
      color: white;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }
    .flex-container {
      height: 100%;
      display: flex;
      flex-direction: row;
      align-items: center;
      border: 1px solid #ccc;
    }
    #map-container {
   flex: 1;
   height: 100%;
   margin: 0;
}

#map {
   height: 100%;
   width: 100%;
}
.navbar{
      /* Place CSS for navigation bar here */
      display: flex;
      flex-direction: column;
      background-color: #007bff;
      width: 100%; /* Set the width to 100% */
      }
    ul {
      list-style: none;
      display: flex;
      flex-wrap: wrap; /* Enable wrapping for multiple columns */
      justify-content: space-around;
      margin: 0; /* Remove default margin for ul */
      padding: 0; /* Remove default padding for ul */
      width: 100%;
    }
    .navbar a {
      background-color: #007bff;
      color: white;
      text-align: center;
      text-decoration: none;
      height: 100%;
      display: flex;
      align-items: center;
  }
  .login-container {
  width: 300px;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
}
h3 {
  text-align: center;
}
.form-group {
  margin-bottom: 15px;
}
label {
  display: block;
  margin-bottom: 5px;
}
#autocomplete-results {
    background-color: white;
    overflow-y: auto;
    position: absolute;
    margin-top: 10px; /* Add some margin to the top */
    z-index: 1; /* Ensure that the results are rendered below the button */
    width: auto; 
    max-height: 200px;
  }
  .result {
    padding: 8px;
    cursor: pointer;
    display: block;
  }

  .result:hover {
    background-color: #ddd;
  }
</style>

</head>
<body>

  <div id="nav_container">
    <nav class="navbar">
      <ul>
        <li><a href="main_citizen.php">Home.</a></li>
        <li>
          <form id="logout-form">
          <button type="submit" id="logout-button" name="logout-submit">Logout</button>
        </form>
        </li>
      </ul>
    </nav>
  </div>

  <div class="flex-container">

    <div class="flex-container" style="flex-grow: 1; flex-direction: column;">
      
      <div class="login-container">
        <h3>Create Offer!</h3>
        <form id="offer-form">
          
          <div class="form-group">
              <label for="item">Item</label>
              <input type="text" id="item" name="item" autocomplete="off" 
              value="<?php echo $announcementData['item_name'];?>" disabled required>
          </div>

          <div id="autocomplete-results"></div>    
          
          <div class="form-group">
              <label>Quantity</label>
              <input type="number" id="quantity" name="quantity" min="1" autocomplete="off" 
              value="<?php echo $announcementData['quantity'];?>" disabled required>
          </div>
            
          <div class="form-group">
            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" autocomplete="off" required>
          </div>

          <div class="form-group"> 
              <label for="longitude">Longitude:</label>
              <input type="text" id="longitude" name="longitude" autocomplete="off" required>
          </div>

          <button type="submit" name="signup-submit" id="submit-button">Register</button>
          
        </form>
          
        <div id="message" class="error-message"></div>

      </div>
    
    </div>  
    
    <div class="flex-container" style="flex-grow: 5;">

      <div id="map-container">
        <div id="map"></div>
      </div>

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
          var formattedLongitude = coordinates.lng.toFixed(6);
          // Update the input field with the formatted latitude
          document.getElementById('latitude').value = formattedLatitude;
          // Update the input field with the formatted longitude
          document.getElementById('longitude').value = formattedLongitude;
        });
      </script>
    </div>  
  </div>  


<script src="offer.js"></script>
</body>
</html>