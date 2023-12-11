<?php
  require "database.php";
  require "config.php";
  // If we are not logged in stuff happens here
  if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
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
    html,body{
      height: 100%;
      margin: 0;
    }
    #map { 
      height: 90%;
      margin-top: 20px;
    }
    button{
      height: 10%;
    }
    .navbar{
      /* Place CSS for navigation bar here */
    }
  </style>    
  </head>
  <body>

  <nav class="navbar">
    <ul>
        <li>
          <form action="includes/logout.php" method="post" id="login-form">
          <button type="submit" id="logout-button" name="logout-submit">Logout</button>
          </form>
        </li>
        <!-- Page for rescuer registration -->
        <li><a href="signup_rescuer.php">Create a Rescuer account.</a></li>
          <!-- Page for adding new categories in database -->
        <li><a href="insert_category.php">Insert categories.</a></li>
        <!--  Page for adding new items in database -->
        <li><a href="insert_item.php">Insert items.</a></li>
        <!-- Page for altering quantities of items in database -->
        <li><a href="item_quantity.php">Change quantity of items here.</a></li>
        <li><a href="create_news.php">Create news.</a></li>
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
    </ul>
  </nav>  
    

  
  
  <!--  Create map for the user -->
  <div id="map">
   <!-- Make map location -->
  <script>
    var map = L.map('map').setView([
          38.2463673403233,21.735140649945635], 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Make marker location
    var marker = L.marker([38.2463673403233, 21.735140649945635]).addTo(map);

    // Make marker popup
    marker.bindPopup("<b>Citizen name</b><br><b>Citizen lastname</b><br><b>Citizen phone</b><br><b>Citizen lastname</b><br>.").openPopup();

    

  </script>
  </div>

  <div id="coordinates"></div>

  <script>
    map.on('click', function (e) {
      var coordinates = e.latlng;
      alert("Coordinates: " + coordinates.lat + ", " + coordinates.lng);
    });
  </script>
    
<?php
  // Error Handling
  $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  
  if(strpos($fullUrl, "error=sqlerror") == true){
    echo "There was an SQL error";
  }
  elseif(strpos($fullUrl, "signup=success") == true){
    echo "Hooray! You Signed up!";
  }
  elseif(strpos($fullUrl, "signup=invalidcarname") == true){
    echo "Invalid car name";
  }
?>
</body>
</html>