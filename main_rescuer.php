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
    button:disabled,
    button[disabled]{
      border: 1px solid #999999;
      background-color: #cccccc;
      color: #666666;
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
        <li>
          <form id="logout-form">
            <button type="submit" id="logout-button" name="logout-submit">Logout</button>
          </form>
        </li>
        <li><button id="loadButton" style="display: none;" disabled>Load</button></li>
        <li><button id="unloadButton" style="display: none;" disabled>Unload</button></li>
      </ul>
    </nav>
  </div>

  <div class="flex-container" style="flex-direction: row;">

    <div class="flex-container" style="flex-direction: column; align-items: flex-start;">
        
      <div>
        <h2>REQUEST PANEL</h2>    
      </div>

      <div class="flex-container" style="align-items: flex-start; max-height:200px; overflow:auto;">
        <table id="request-history" class="history">
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
        <div id="message"></div>
      </div>

      <div>
        <h2>OFFER PANEL</h2>    
      </div>

      <div class="flex-container" style="align-items: flex-start; max-height:200px; overflow:auto;">
        <table id="offer-history" class="history">
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
        <div id="message"></div>
      </div>

      <div>
        <h2>Rescuer inventory</h2>    
      </div>

      <div class="flex-container" style="align-items: flex-start; max-height: 200px; overflow: auto;">
        <table id="car-history" class="history">
          <tr>
            <th>Item name</th>
            <th>Quantity</th>
          </tr>
        </table>
        <div id="message"></div>
      </div>

    </div>

    <div style="position: absolute; bottom: 110px; left: 210px;">
      <span style="color: red;">Rescuer: Red, </span>
      <span style="color: yellow;">Not active Request: Yellow, </span>
      <span style="color: blue;">Active Request: Blue, </span> <br>
      <span style="color: green;">Not active Offer: Green, </span>
      <span style="color: purple;">Active Offer: Purple</span>
    </div>
      
    <div class="flex-container" style="flex-grow: 5; align-items: flex-start; flex-direction:column;">

      <div id="map-container" style="height: 90%; width: 100%;">
        <div id="map"></div>
      </div>

      <div class="flex-container" style="width: 100%; height:auto; border: none; padding: 0;">
        <button id="toggleLinesButton">Lines <br>Toggle</button>
        <button id="toggleNotActiveRequests">Not active requests Toggle</button>
        <button id="toggleActiveRequets">Active requests Toggle</button>
        <button id="toggleNotActiveOffers">Not active offers Toggle</button>
        <button id="toggleActiveOffers">Active offers <br> Toggle</button>
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
        let markers = {};
        let popupContent;
        let showLines = true;
        let showLinesMarkerBlue = true;
        let showLinesMarkerYellow = true;
        let showLinesMarkerPurple = true;
        let showLinesMarkerGreen = true;
        let blueMarkers = [];
        let yellowMarkers = [];
        let purpleMarkers = [];
        let greenMarkers = [];
        let temp = true;
        let polylines = [];
        let taskID;

        // Get base coordinates from database
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'includes/base_to_json.php', true);
        xhr.setRequestHeader('Content-type', 'application/json');

        xhr.onload = function () {
          // Create marker function
          function createAndBindMarker(lat, lng) {
              marker = L.marker([lat, lng]).addTo(map);
              marker.bindPopup('<b>Base.</b><br>'
              ).openPopup();
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

              // console.log("Car Name: " + carName + ", Longitude: " + lng + ", Latitude: " + lat);

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
                  // Remove previous polylines
                  removePolylines();
                  // Delete purple, blue Markers contents 
                  purpleMarkers = [];
                  blueMarkers = [];
                  // Load requests and offers again for the correct polyline lineup
                  offersCoordinates();
                  requestsCoordinates();
                } 
                else {
                  // Revert the marker position to the original position
                  event.target.setLatLng(originalPosition);
                }
              });
            }
            else {
              // console.log("No data found for the user.");
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
              // console.log(this.responseText);
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
        function requestsCoordinates(){
          let xhr5 = new XMLHttpRequest();
xhr5.open('GET', 'includes/request-coordinates-rescuer.inc.php', true);
xhr5.setRequestHeader('Content-type', 'application/json');

xhr5.onload = function () {
    if (xhr5.status == 200) {
        let response = JSON.parse(this.responseText);
        let counter = 1;

        response.forEach(function (item) {
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

            let requestMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map);

            if (active == 0) {
                receiveButton = '<button id="receiveRequestButton_' + counter + '" onclick="receiveTask(' + taskID + ')">Receive task</button>';
                color = 'yellow';
                yellowMarkers.push(requestMarker);
            } else {
                color = 'blue';
                blueMarkers.push(requestMarker);
            }

            // Set marker icon HTML based on color
            requestMarker.setIcon(L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: ' + color + ';" class="marker-dot"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            }));

            // console.log("Name: " + name + "Lastname: " + lastname +
            //     "Phone: " + phone + "Publish Date: " + publishDate + "Item name: " + itemName +
            //     "Quantity: " + quantity + ", Withdrawal Date: " + withdrawDate + ", Car name: " + carName);

            if (active == 1 && showLines) {
                let latlngs = [rescuerMarker.getLatLng(), requestMarker.getLatLng()];
                let polyline = L.polyline(latlngs, { color: 'red' }).addTo(map);
                markers['polylineRequest_' + counter] = polyline;
            }



            requestMarker.bindPopup('<span style="display: none;"><b>Task_id:' + taskID + '.</b></span>' + '<b>Name:' + name + '.</b><br><b>Lastname:' + lastname +
                '.</b><br><b>Phone:' + phone + '.</b><br><b>Publish Date:' + publishDate +
                '.</b><br><b>Item name:' + itemName + '.</b><br><b>Quantity:' + quantity +
                '.</b><br><b>Withdrawal Date:' + withdrawDate + '.</b><br><b>Car name:' + carName +
                '</b><br>' + receiveButton
            ).openPopup();

            counter++;
        });
        enableBlueButtonIfWithinDistance();
        enableBaseButtonIfWithinDistance();

    }
};

xhr5.send();



        }

        // Load the Requests
        requestsCoordinates();
        // Load the Offers
        offersCoordinates();        

        // Get offers coordinates from database function
        function offersCoordinates(){
          let xhr6 = new XMLHttpRequest();
xhr6.open('GET', 'includes/offer-coordinates-rescuer.inc.php', true);
xhr6.setRequestHeader('Content-type', 'application/json');

xhr6.onload = function () {
    if (xhr6.status == 200) {
        let response = JSON.parse(this.responseText);
        let counter = 1;

        response.forEach(function (item) {
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

            let offerMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map);

            if (active == 0) {
                receiveButton = '<button id="receiveOfferButton_' + counter + '" onclick="receiveTask(' + taskID + ')">Receive task</button>';
                color = 'green';
                greenMarkers.push(offerMarker);
            } else {
                color = 'purple';
                purpleMarkers.push(offerMarker);
            }

            // Set marker icon HTML based on color
            offerMarker.setIcon(L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: ' + color + ';" class="marker-dot"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            }));

            // console.log("Name: " + name + "Lastname: " + lastname +
            //     "Phone: " + phone + "Publish Date: " + publishDate + "Item name: " + itemName +
            //     "Quantity: " + quantity + ", Withdrawal Date: " + withdrawDate + ", Car name: " + carName);

            if (active == 1 && showLines) {
                let latlngs = [rescuerMarker.getLatLng(), offerMarker.getLatLng()];
                let polyline = L.polyline(latlngs, { color: 'red' }).addTo(map);
                markers['polylineOffer_' + counter] = polyline;
            }


            offerMarker.bindPopup('<span style="display: none;"><b>Task_id:' + taskID + '.</b></span>' + '<b>Name:' + name + '.</b><br><b>Lastname:' + lastname +
                '.</b><br><b>Phone:' + phone + '.</b><br><b>Publish Date:' + publishDate +
                '.</b><br><b>Item name:' + itemName + '.</b><br><b>Quantity:' + quantity +
                '.</b><br><b>Withdrawal Date:' + withdrawDate + '.</b><br><b>Car name:' + carName +
                '</b><br>' + receiveButton
            ).openPopup();

            counter++;
        });
    enablePurpleButtonIfWithinDistance();

    }
    console.log(purpleMarkers[0]);
    
};

xhr6.send();
        }

// REQUEST PANEL 
let xhr9 = new XMLHttpRequest();
xhr9.open('GET', 'includes/request-history.inc.php', true);
xhr9.setRequestHeader('Content-type', 'application/json');
xhr9.onload= function(){
  console.log(this.responseText);
  if(xhr9.status == 200){
    let jsonData = JSON.parse(this.responseText);

    let output = '<tr>' + '<th>' + 'Name' + '</th>' + '<th>' + 'Lname' + '</th>' + '<th>' + 'Phone' + 
      '</th>' + '<th>' + 'Publish Date' + '</th>' + '<th>' + 'Item' + '</th>' +
      '<th>' + 'Qty.' + '</th>' + '<th>' + 'Done' + '</th>' + '<th>' + 'Cancel' + '</th>' + '</tr>';
      for (let i = 0; i < jsonData.length; i++) {
        output += '<tr>' + '<td>' + jsonData[i].user_name + '</td>' +
        '<td>' + jsonData[i].user_lastname + '</td>' +
        '<td>' + jsonData[i].user_phone + '</td>' +
        '<td>' + jsonData[i].publish_date + '</td>' + 
        '<td>' + jsonData[i].inventory_item_name + '</td>' +
        '<td>' + jsonData[i].task_quantity + '</td>' +
        '<td>' + '<button class="done-button" id="doneRequestButton_' + i + '" onclick="doneTask(' + jsonData[i].task_id + ')" disabled>' + 'Done' + '</button>' + '</td>' +
        '<td>' + '<button class="cancel-button" id="cancelButton_' + i + '" onclick="cancel(' + jsonData[i].task_id + ')">' + 'Cancel' + '</button>' + '</td>' +
         '</tr>' + '<span style="display:none;">Task_id =' + jsonData[i].task_id + '</span>';
      } 
      document.getElementById('request-history').innerHTML = output;
    } 
    else {
      // Handle the error
      console.error('Error fetching JSON data. Status code: ' + xhr.status);
  }
};
xhr9.send();

// OFFER PANEL
let xhr10 = new XMLHttpRequest();
xhr10.open('GET', 'includes/offer-history.inc.php', true);
xhr10.setRequestHeader('Content-type', 'application/json');
xhr10.onload= function(){
  console.log(this.responseText);
  if(xhr10.status == 200){
    let jsonData = JSON.parse(this.responseText);

    let output = '<tr>' + '<th>' + 'Name' + '</th>' + '<th>' + 'Lname' + '</th>' + '<th>' + 'Phone' + 
      '</th>' + '<th>' + 'Publish Date' + '</th>' + '<th>' + 'Item' + '</th>' +
      '<th>' + 'Qty.' + '</th>' + '<th>' + 'Done' + '</th>' + '<th>' + 'Cancel' + '</th>' + '</tr>';
      for (let i = 0; i < jsonData.length; i++) {
        output += '<tr>' + '<td>' + jsonData[i].user_name + '</td>' +
        '<td>' + jsonData[i].user_lastname + '</td>' +
        '<td>' + jsonData[i].user_phone + '</td>' +
        '<td>' + jsonData[i].publish_date + '</td>' + 
        '<td>' + jsonData[i].inventory_item_name + '</td>' +
        '<td>' + jsonData[i].task_quantity + '</td>' +
        '<td>' + '<button class="done-button" id="doneOfferButton_' + i + '" onclick="doneTask(' + jsonData[i].task_id + ')" disabled>' + 'Done' + '</button>' + '</td>' +
        '<td>' + '<button class="cancel-button" id="cancelButton_' + i + '" onclick="cancel(' + jsonData[i].task_id + ')">' + 'Cancel' + '</button>' + '</td>' + '</tr>'
        + '<span style="display:none;">Task_id =' + jsonData[i].task_id + '</span>';
      } 
      document.getElementById('offer-history').innerHTML = output;
    } 
    else {
      // Handle the error
      console.error('Error fetching JSON data. Status code: ' + xhr.status);
  }
};
xhr10.send();

// Rescuer inventory PANEL
let xhr12 = new XMLHttpRequest();
xhr12.open('GET', 'includes/car-history.inc.php', true);
xhr12.setRequestHeader('Content-type', 'application/json');
xhr12.onload= function(){
  console.log(this.responseText);
  if(xhr12.status == 200){
    let jsonData = JSON.parse(this.responseText);

    let output = '<tr>' + '<th>' + 'Item name' + '</th>' + '<th>' + 'Quantity' + '</th>' + '</tr>';
      for (let i = 0; i < jsonData.length; i++) {
        output += '<tr>' + '<td>' + jsonData[i].inventory_item_name + '</td>' +
        '<td>' + jsonData[i].quantity + '</td>' + '</tr>'
      } 
      document.getElementById('car-history').innerHTML = output;
    } 
    else {
      // Handle the error
      console.error('Error fetching JSON data. Status code: ' + xhr.status);
  }
};
xhr12.send();

// Function for receiving tasks
function receiveTask(taskID) {
    console.log("Received task with task_id: " + taskID);
    // Assign task to rescuer
    let xhr7 = new XMLHttpRequest();
    xhr7.open('POST', 'includes/assign-task.inc.php', true);
    xhr7.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    console.log('Data being sent to server:', 'task_id=' + taskID);

    xhr7.onload = function(){
      console.log(this.responseText); // Log the server response
      if(xhr7.status == 200){
        let response = JSON.parse(this.responseText);
        document.getElementById('message').innerHTML = response.message;

        if(response.success){
          window.location.href = response.redirect;
        }
      }
    };
    xhr7.send('task_id=' + taskID);
  }


// Functions for toggles
// Function for Lines
function toggleLines() {
  if(showLinesMarkerBlue && showLinesMarkerPurple == true){
    showLines = !showLines;
    for (let key in markers) {
      if (markers.hasOwnProperty(key) && key.startsWith('polylineRequest_')) {
        let polyline = markers[key];
        if (showLines) {
          map.addLayer(polyline);
        } else {
          map.removeLayer(polyline);
        }
      }
    }
  for (let key in markers) {
    if (markers.hasOwnProperty(key) && key.startsWith('polylineOffer_')) {
      let polyline = markers[key];
      if (showLines) {
        map.addLayer(polyline);
      } else {
        map.removeLayer(polyline);
      }
    }
  }
  }
  else if(showLinesMarkerBlue == true && showLinesMarkerPurple == false){
    temp = !temp;
    for (let key in markers) {
    if (markers.hasOwnProperty(key) && key.startsWith('polylineRequest_')) {
      let polyline = markers[key];
      if (temp) {
        map.removeLayer(polyline);
      } else {
        map.addLayer(polyline);
      }
    }
  }
  }
  else if(showLinesMarkerBlue == false && showLinesMarkerPurple == true){
    temp = !temp;
    for (let key in markers) {
    if (markers.hasOwnProperty(key) && key.startsWith('polylineOffer_')) {
      let polyline = markers[key];
      if (temp) {
        map.addLayer(polyline);
      } else {
        map.removeLayer(polyline);
      }
    }
  }
  }
}

// Remove polylines function
function removePolylines() {
  for (let key in markers) {
    if (markers.hasOwnProperty(key) && key.startsWith('polylineRequest_')) {
      let polyline = markers[key];
      map.removeLayer(polyline);
      delete markers[key];
    }
  }
  for (let key in markers) {
    if (markers.hasOwnProperty(key) && key.startsWith('polylineOffer_')) {
      let polyline = markers[key];
      map.removeLayer(polyline);
      delete markers[key];
    }
  }
}

// Function for active requests
function toggleBlueMarkers() {
    showLinesMarkerBlue = !showLinesMarkerBlue;
    blueMarkers.forEach(function (marker) {
        if (map.hasLayer(marker)) {
            map.removeLayer(marker);
        } else {
            map.addLayer(marker);
        }
    });
    for (let key in markers) {
    if (markers.hasOwnProperty(key) && key.startsWith('polylineRequest_')) {
      let polyline = markers[key];
      if (showLinesMarkerBlue) {
        map.addLayer(polyline);
      } else {
        map.removeLayer(polyline);
      }
    }
  }
}
// Function for not active requests
function toggleYellowMarkers() {
    showLinesMarkerYellow = !showLinesMarkerYellow;
    yellowMarkers.forEach(function (marker) {
        if (map.hasLayer(marker)) {
            map.removeLayer(marker);
        } else {
            map.addLayer(marker);
        }
    });
}

// Function for active offers
function togglePurpleMarkers() {
    showLinesMarkerPurple = !showLinesMarkerPurple;
    purpleMarkers.forEach(function (marker) {
        if (map.hasLayer(marker)) {
            map.removeLayer(marker);
        } else {
            map.addLayer(marker);
        }
    });
    for (let key in markers) {
    if (markers.hasOwnProperty(key) && key.startsWith('polylineOffer_')) {
      let polyline = markers[key];
      if (showLinesMarkerPurple) {
        map.addLayer(polyline);
      } else {
        map.removeLayer(polyline);
      }
    }
  }
}

// Function for not active offers
function toggleGreenMarkers() {
    showLinesMarkerGreen = !showLinesMarkerGreen;
    greenMarkers.forEach(function (marker) {
        if (map.hasLayer(marker)) {
            map.removeLayer(marker);
        } else {
            map.addLayer(marker);
        }
    });
}


// Function for the activation of Request Done button
function enableBlueButtonIfWithinDistance() {
      for (let i = 0; i < blueMarkers.length; i++) {
        let currentBlueMarker = blueMarkers[i];
        let distance = currentBlueMarker.getLatLng().distanceTo(rescuerMarker.getLatLng());

        console.log(`Distance from Blue Marker ${i + 1} to Target Point: ${distance} meters`);

        // Now you can compare the distance and take further actions
        if (distance < 50) {
            document.getElementById('doneRequestButton_' + i).disabled = false;
        }
        else{
          document.getElementById('doneRequestButton_' + i).disabled = true;
        }
      }
    }


    // Function for the activation of Offer Done button
function enablePurpleButtonIfWithinDistance() {
      for (let i = 0; i < purpleMarkers.length; i++) {
        let currentPurpleMarker = purpleMarkers[i];
        let distance = currentPurpleMarker.getLatLng().distanceTo(rescuerMarker.getLatLng());

        console.log(`Distance from Purple Marker ${i + 1} to Target Point: ${distance} meters`);

        // Now you can compare the distance and take further actions
        if (distance < 50) {
            document.getElementById('doneOfferButton_' + i).disabled = false;
        }
        else{
          document.getElementById('doneOfferButton_' + i).disabled = true;
        }
      }
    }

// Function for loading/unloading link activation
    function enableBaseButtonIfWithinDistance() {
      
        let distance = marker.getLatLng().distanceTo(rescuerMarker.getLatLng());

        console.log(`Distance from Base Marker to Target Point: ${distance} meters`);

        // Now you can compare the distance and take further actions
        if (distance < 100) {
            document.getElementById('loadButton').style.display = 'block';
            document.getElementById('loadButton').disabled = false;
            document.getElementById('unloadButton').style.display = 'block';
            document.getElementById('unloadButton').disabled = false;
        }
        else{
          document.getElementById('loadButton').style.display = 'none';
          document.getElementById('loadButton').disabled = true;
          document.getElementById('unloadButton').style.display = 'none';
          document.getElementById('unloadButton').disabled = true;
        }
      
    }

    document.getElementById('loadButton').addEventListener('click', function() {
        // Your action here, for example, navigating to load-unload.php
        window.location.href = 'load.php';
    });

    document.getElementById('unloadButton').addEventListener('click', function() {
        // Your action here, for example, navigating to load-unload.php
        window.location.href = 'unload.php';
    });

    // Add the cancel function
function cancel(taskId) {
    console.log('Cancel button clicked for Task ID:', taskId);

    // Cancel task to rescuer
    let xhr11 = new XMLHttpRequest();
    xhr11.open('POST', 'includes/cancel-task.inc.php', true);
    xhr11.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    console.log('Data being sent to server:', 'task_id=' + taskId);

    xhr11.onload = function(){
      console.log(this.responseText); 
      if(xhr11.status == 200){
        let response = JSON.parse(this.responseText);
        document.getElementById('message').innerHTML = response.message;

        if(response.success){
          window.location.href = response.redirect;
        }
      }
    };
    xhr11.send(JSON.stringify({ task_id: taskId }));
    }

    // Function for done button
    function doneTask(taskId) {
  // Implement your logic here
  console.log('Task marked as done. Task ID: ' + taskId);

  let xhr13 = new XMLHttpRequest();
  xhr13.open('POST', 'includes/done-task.inc.php', true);
  xhr13.setRequestHeader('Content-type', 'application/json');

  let requestBody = JSON.stringify({ task_id: taskId });

  xhr13.onload = function () {
    if (xhr13.status === 200) {
      let response = JSON.parse(this.responseText);
      console.log(response);
      console.log('Task marked as done successfully.');
      if(response.success){
          window.location.href = response.redirect;
        }
    } else {
      console.error('Error marking task as done. Status code: ' + xhr13.status);
    }
  };

  xhr13.send(requestBody);
}



// Event Listeners for toggle functions
document.getElementById('toggleLinesButton').addEventListener('click', toggleLines);
document.getElementById('toggleNotActiveRequests').addEventListener('click', toggleYellowMarkers);
document.getElementById('toggleActiveRequets').addEventListener('click', toggleBlueMarkers);
document.getElementById('toggleNotActiveOffers').addEventListener('click', toggleGreenMarkers);
document.getElementById('toggleActiveOffers').addEventListener('click', togglePurpleMarkers);


console.log(blueMarkers);
console.log(purpleMarkers);
console.log(markers);



      </script>
    </div>  

  </div>
  <script src="main_rescuer.js"></script>
</body>
</html>