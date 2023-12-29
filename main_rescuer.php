<?php
  require "database.php";
  require "config.php";
  // If we are not logged in stuff happens here
  if ($_SESSION['role'] != 'rescuer') {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rescuer page</title>
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
    #map{ 
      height: 90%;
    }
  </style>
</head>
<body>
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
</body>
</html>