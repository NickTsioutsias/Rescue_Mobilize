<?php
  require "database.php";
  require "config.php";
  // If we are not logged in stuff happens here
  if ($_SESSION['role'] != 'rescuer') {
    header("Location: login.html");
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
  h2 {
    text-align: center;
  }
  .history {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    .history td, .history th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    .history tr:nth-child(even){background-color: #f2f2f2;}

    .history tr:hover {background-color: #ddd;}

    .history th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #007bff;
      color: white;
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
        <li><a href="car-inventory.php">Car inventory.</a></li>
        <li>
          <form id="logout-form">
          <button type="submit" id="logout-button" name="logout-submit">Logout</button>
        </form>
        </li>
      </ul>
    </nav>
  </div>

  <div class="flex-container" style="flex-direction: row;">

    <div class="flex-container" style="flex-direction: column; align-items: flex-start;">
        
      <div>
        <h2>TASK PANEL</h2>    
      </div>

      <div class="flex-container" style="align-items: flex-start;">
        <table id="task-history" class="history">
          <tr>
            <th>Name</th>
            <th>Lastname</th>
            <th>Phone</th>
            <th>Publish Date</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Done button</th>
            <th>Cancel button</th>
          </tr>
        </table>
      </div>   
    </div>
      
    <div class="flex-container" style="flex-grow: 5;">

      <div id="map-container">
        <div id="map"></div>
      </div>

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
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'includes/base_to_json.php', true);
        xhr.setRequestHeader('Content-type', 'application/json');

        xhr.onload = function () {
          // Create marker function
          function createAndBindMarker(lat, lng) {
              marker = L.marker([lat, lng]).addTo(map);
              marker.bindPopup('<b>Base.</b><br>').openPopup();
          }

          if (xhr.status == 200) {
              let jsonData = JSON.parse(this.responseText);
              createAndBindMarker(jsonData[0].lat, jsonData[0].lng);
          }
        };
        xhr.send();

        // Get rescuers coordinates from the database
        let xhr2 = new XMLHttpRequest();
        xhr2.open('GET', 'includes/rescuer_cords.inc.php', true);
        xhr2.setRequestHeader('Content-type', 'application/json');

        xhr2.onload = function () {
          if (xhr2.status == 200) {
            let response = JSON.parse(this.responseText);

            // Check if there is a single row in the response
            if (response.success && response.data) {
              let item = response.data;
              let carName = item.car_name;
              let lng = item.lng;
              let lat = item.lat;
              let currTask = item.curr_task;

              console.log("Car Name: " + carName + ", Longitude: " + lng + ", Latitude: " + lat);

              rescuerMarker = L.marker([lat, lng], {
                draggable: true,
                icon: L.divIcon({
                  className: 'custom-marker',
                  html: '<div style="background-color: red;" class="marker-dot"></div>',
                  iconSize: [20, 20], // Adjust the size if needed
                  iconAnchor: [10, 10] // Adjust the anchor point if needed
                })
              }).addTo(map);

              rescuerMarker.bindPopup('<b>Car Name:' + carName + '.</b><br><b>Tasks:' + currTask + '</b>').openPopup();

              // Store the original position
              let originalPosition = rescuerMarker.getLatLng();
              // Click and Drag function
              rescuerMarker.on('dragend', function (event) {
                let currentPosition = event.target.getLatLng();
                let confirmation = confirm('Do you want to change your coordinates?');
                if (confirmation) {
                  // Store new rescuer location in database
                  insertIntoDatabase(currentPosition.lat, currentPosition.lng);
                } 
                else {
                  // Revert the marker position to the original position
                  event.target.setLatLng(originalPosition);
                }
              });
            }
            else {
              console.log("No data found for the user.");
            }
          }
        };

        xhr2.send();


        // Function to insert coordinates into the database
        function insertIntoDatabase(latitude, longitude) {
          let xhr3 = new XMLHttpRequest();
          xhr3.open('POST', 'includes/new_rescuer_loc.inc.php', true);
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

        // Get requests coordinates from database
        let xhr5 = new XMLHttpRequest();
        xhr5.open('GET', 'includes/request-coordinates-rescuer.inc.php', true);
        xhr5.setRequestHeader('Content-type', 'application/json');

        xhr5.onload = function(){
          if(xhr5.status == 200){
            let response = JSON.parse(this.responseText);
            let counter = 1;
            response.forEach(function(item){
              let name = item.user_name;
              let lastname = item.user_lastname;
              let phone = item.user_phone;
              let publishDate = item.publish_date;
              let itemName = item.inventory_item_name;
              let quantity = item.task_quantity;
              let withdrawDate = item.withdraw_date;
              let carName = item.rescuer_car_name;
              let lng = item.lng; 
              let lat = item.lat;
              let active = item.active;
              let receiveButton = '';
              let color;
              if(active == 0){
                receiveButton = '<button id="receiveRequestButton_' + counter + '">Receive task</button>';
                color = 'yellow';
              }
              else{
                color = 'purple';
              }
              console.log("Name: " + name + "Lastname: " + lastname +
              "Phone: " + phone +"Publish Date: " + publishDate +"Item name: " + itemName +
              "Quantity: " + quantity + ", Withdrawal Date: " + withdrawDate + ", Car name: " + carName);
              requestMarker = L.marker([lat, lng], {icon: L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color:' + color + ';" class="marker-dot"></div>',
                iconSize: [20, 20], // Adjust the size if needed
                iconAnchor: [10, 10] // Adjust the anchor point if needed
              })}).addTo(map);
              requestMarker.bindPopup('<b>Name:' + name + '.</b><br><b>Lastname:' + lastname +
              '.</b><br><b>Phone:' + phone + '.</b><br><b>Publish Date:' + publishDate +
              '.</b><br><b>Item name:' + itemName + '.</b><br><b>Quantity:' + quantity +
              '.</b><br><b>Withdrawal Date:' + withdrawDate + '.</b><br><b>Car name:' + carName +
              '</b><br>' + receiveButton
              ).openPopup();
              counter++;
            });
          }

        };
        xhr5.send();

        // Get offers coordinates from database
        let xhr6 = new XMLHttpRequest();
        xhr6.open('GET', 'includes/offer-coordinates-rescuer.inc.php', true);
        xhr6.setRequestHeader('Content-type', 'application/json');

        xhr6.onload = function(){
          if(xhr6.status == 200){
            let response = JSON.parse(this.responseText);
            let counter = 1; 
            response.forEach(function(item){
              let taskID = item.task_id;
              let name = item.user_name;
              let lastname = item.user_lastname;
              let phone = item.user_phone;
              let publishDate = item.publish_date;
              let itemName = item.inventory_item_name;
              let quantity = item.task_quantity;
              let withdrawDate = item.withdraw_date;
              let carName = item.rescuer_car_name;
              let lng = item.lng; 
              let lat = item.lat;
              let active = item.active;
              let receiveButton = '';
              let color;
              if(active == 0){
                receiveButton = '<button id="receiveOfferButton_' + counter + '">Receive task</button>';
                color = 'green';
              }
              else{
                color = 'purple';
              }
              console.log("Name: " + name + "Lastname: " + lastname +
              "Phone: " + phone +"Publish Date: " + publishDate +"Item name: " + itemName +
              "Quantity: " + quantity + ", Withdrawal Date: " + withdrawDate + ", Car name: " + carName);
              offerMarker = L.marker([lat, lng], {icon: L.divIcon({
                  className: 'custom-marker',
                  html: '<div style="background-color: ' + color + ';" class="marker-dot"></div>',
                  iconSize: [20, 20], // Adjust the size if needed
                  iconAnchor: [10, 10] // Adjust the anchor point if needed
              })}).addTo(map);
              offerMarker.bindPopup('<span style="display: none;"><b>Task_id:' + taskID + '.</b></span>' + '<b>Name:' + name + '.</b><br><b>Lastname:' + lastname +
              '.</b><br><b>Phone:' + phone + '.</b><br><b>Publish Date:' + publishDate +
              '.</b><br><b>Item name:' + itemName + '.</b><br><b>Quantity:' + quantity +
              '.</b><br><b>Withdrawal Date:' + withdrawDate + '.</b><br><b>Car name:' + carName +
              '</b><br>' + receiveButton
              ).openPopup();
              counter++;
            });
          }

        };
        xhr6.send();

        // Event listener for each Recieve offer button
        document.getElementById('map-container').addEventListener('click', function (event) {
          // Check if the clicked element is a button with an ID starting with "receiveOfferButton_"
          if (event.target.tagName === 'BUTTON' && event.target.id.startsWith('receiveOfferButton_')) {
            // Extract the unique identifier from the button ID
            let buttonIdParts = event.target.id.split('_');
            let buttonIdentifier = buttonIdParts[1];

            // Handle the click event for the specific button
            console.log('Receive request clicked for button with identifier:', buttonIdentifier);
            // Add your logic here, e.g., call a function to handle the task receipt
            }
        });

        // Event listener for each Recieve request button
        document.getElementById('map-container').addEventListener('click', function (event) {
          // Check if the clicked element is a button with an ID starting with "receiveRequestButton_"
          if (event.target.tagName === 'BUTTON' && event.target.id.startsWith('receiveRequestButton_')) {
            // Extract the unique identifier from the button ID
            let buttonIdParts = event.target.id.split('_');
            let buttonIdentifier = buttonIdParts[1];

            // Handle the click event for the specific button
            console.log('Receive request clicked for button with identifier:', buttonIdentifier);
            // Add your logic here, e.g., call a function to handle the task receipt
            }
        });



      </script>
    </div>  

  </div>

</body>
</html>