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
    p{
      color: red;
    }
    body {
      font-family: Arial, sans-serif;
      display: flex;
      /* containers should be in rows */
      flex-direction: column;
      justify-content: space-between;
      margin: 0;
      height: 100vh; /* Make sure the body takes the full viewport height */
    }
    #nav-container {
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
    button{
      height: 10%;
    }
    .navbar{
      /* Place CSS for navigation bar here */
      display: flex;
      flex-direction: column;
    }
    ul {
      list-style: none;
      display: flex;
      flex-wrap: wrap; /* Enable wrapping for multiple columns */
      justify-content: space-around;
    }
    .custom-marker {
    display: inline-block;
}

  .marker-dot {
      width: 20px;
      height: 20px;
      border-radius: 50%;
  }
  </style>    
  </head>
  <body>
  <div id="nav_container">
  <nav class="navbar">
    <ul>
        <li>
          <form id="logout-form">
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
  </div>
    
    

  
  
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
    // Declare marker globally to make it accessible in other functions
    let marker;


// Get base coordinates from database
let xhr2 = new XMLHttpRequest();
xhr2.open('GET', 'includes/base_to_json.php', true);
xhr2.setRequestHeader('Content-type', 'application/json');

xhr2.onload = function () {
    
    // Create marker function
    function createAndBindMarker(lat, lng) {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.bindPopup('<b>Base.</b>').openPopup();

        // Store the original position
        let originalPosition = marker.getLatLng();
        // Click and Drag function
        marker.on('dragend', function (event) {
            let currentPosition = event.target.getLatLng();
            let confirmation = confirm('Do you want to change the Base coordinates?');
            if (confirmation) {
              // Store new Base location in database
              insertIntoDatabase(currentPosition.lat, currentPosition.lng);
            } else {
                // Revert the marker position to the original position
                event.target.setLatLng(originalPosition);
            }
        });
    }

    if (xhr2.status == 200) {
        let jsonData = JSON.parse(this.responseText);
        createAndBindMarker(jsonData[0].lat, jsonData[0].lng);
    }
};
xhr2.send();

// Function to insert coordinates into the database
function insertIntoDatabase(latitude, longitude) {
  let xhr3 = new XMLHttpRequest();
  xhr3.open('POST', 'includes/new_base_loc.inc.php', true);
  xhr3.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr3.onload = function(){
    if(xhr3.status == 200){
      console.log(this.responseText);
      response = JSON.parse(this.responseText);
      if(response.success){
        marker.setLatLng(marker.getLatLng());
      }
      else{
        console.log('Error');
      }
    }
  };
  xhr3.send('latitude=' + latitude + '&longitude=' + longitude);
}


// Get resquers coordinates from database
let xhr4 = new XMLHttpRequest();
xhr4.open('GET', 'includes/rescuer_to_json.php', true);
xhr4.setRequestHeader('Content-type', 'application/json');

xhr4.onload = function(){
  if(xhr4.status == 200){
    let response = JSON.parse(this.responseText);
    response.forEach(function(item){
      let carName = item.car_name;
      let lng = item.lng;
      let lat = item.lat;
      let currTask = item.curr_task;
      console.log("Car Name: " + carName + ", Longitude: " + lng + ", Latitude: " + lat);
      rescuerMarker = L.marker([lat, lng], {icon: L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: red;" class="marker-dot"></div>',
                iconSize: [20, 20], // Adjust the size if needed
                iconAnchor: [10, 10] // Adjust the anchor point if needed
            }), draggable: true}).addTo(map);
      rescuerMarker.bindPopup('<b>Car Name:' + carName + '.</b><br><b>Tasks:' + currTask + '</b>').openPopup();
    });
    // marker = L.marker([jsonData[0].lat, jsonData[0].lng], {draggable: true}).addTo(map);
    // marker.bindPopup('<b>Base.</b>').openPopup();
  }

};
xhr4.send();



    // Make marker location
    // let marker = L.marker([38.2463673403233, 21.735140649945635], {draggable: true}).addTo(map);

    // marker.on('dragend', function(event){
    //   let marker = event.target;
    //   let position = marker.getLatLng();

    //   // Update the content of the popup with the new coordinates
    //  marker.bindPopup('Marker Position: ' + position.toString()).openPopup();
    // });
    // // Make marker popup
    // marker.bindPopup('<b>Base</b><br>' + marker.getLatLng() + '.').openPopup();

    

  </script>
  </div>

  <div id="coordinates"></div>

  <script>
    map.on('click', function (e) {
      let coordinates = e.latlng;
      alert("Coordinates: " + coordinates.lat + ", " + coordinates.lng);
    });
  </script>

  <div id='message'></div>
    
<?php
  // Error Handling
  $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  
  if(strpos($fullUrl, "error=sqlerror") == true){
    echo "There was an SQL error";
  }
  elseif(strpos($fullUrl, "signup=success") == true){
    echo "Hooray! You Signed up!";
  }
  elseif(strpos($fullUrl, "signupcategory=success") == true){
    echo "Hooray! You created a category";
  }
  elseif(strpos($fullUrl, "signup=invalidcarname") == true){
    echo "Invalid car name";
  }
  
?>
<script src="main_admin.js"></script>
</body>
</html>