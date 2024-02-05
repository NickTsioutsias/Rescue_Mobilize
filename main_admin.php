<?php
  require "database.php";
  require "config.php";
  // If we are not logged in stuff happens here
  if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");  
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

    .error-message {
      color: red;
      text-align: center;
      margin-top: 10px;
    }

    #nav_container {
      /* form should take a third of the page */
      width: 100%;
      padding: 20px;
      padding: 0;
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

    #map-container {
      /* map should take rest of page */
      flex: 1;
    }

    #map {
      height: 100%;
      width: 100%;
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
      <!-- Page for rescuer registration -->
      <li><a href="signup_rescuer.php">Create a Rescuer account.</a></li>
        <!-- Page for adding new categories in database -->
      <li><a href="insert_category.php">Insert categories.</a></li>
      <!--  Page for adding new items in database -->
      <li><a href="insert_item.php">Insert items.</a></li>
      <!-- Page for altering quantities of items in database -->
      <li><a href="item_quantity.php">Change quantity of items here.</a></li>
      <li><a href="announcements.php">Create announcements</a></li>
      <li><a href="inventory.php">Inventory</a></li>
      <li><a href="chartTest.html">Charts</a></li>
      <li>
        <form id="logout-form">
        <button type="submit" id="logout-button" name="logout-submit">Logout</button>
        </form>
      </li>
    </ul>
  </nav>
  </div>
    
    

  
  
  <!--  Create map for the user -->
  <div id="map">
   <!-- Make map location -->
  <script>

    let greenMarkers = [];
    let yellowMarkers = [];

    var map = L.map('map').setView([
          38.2463673403233,21.735140649945635], 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    // Declare marker globally to make it accessible in other functions
    let marker;
    let markers = {};
    let taskID;
    let offerMarkersMap = {};
    let requestMarkersMap = {};
    let polylines = [];


// Get base coordinates from database
let xhr2 = new XMLHttpRequest();
xhr2.open('GET', 'includes/base_to_json.php', true);
xhr2.setRequestHeader('Content-type', 'application/json');

xhr2.onload = function () {
    
    // Create marker function
    function createAndBindMarker(lat, lng) {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.bindPopup('<b>Base.</b><br>').openPopup();

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


// Assume getCarInv function is defined before this code

// Get rescuers coordinates from database
// Get rescuers coordinates from database
let xhr4 = new XMLHttpRequest();
xhr4.open('GET', 'includes/rescuer_to_json.inc.php', true);
xhr4.setRequestHeader('Content-type', 'application/json');

xhr4.onload = function () {
  if (xhr4.status == 200) {
    let responseObject = JSON.parse(this.responseText);

    // Create an object to store quantities and names for each resc_id
    let quantitiesAndNames = {};

    Object.keys(responseObject).forEach(function (rescId) {
      let arrayForRescId = responseObject[rescId];

      arrayForRescId.forEach(function (item) {
        let carName = item.car_name;
        let lng = item.lng;
        let lat = item.lat;
        let currTask = item.curr_task;
        let quantity = item.quantity;
        let name = item.name;

        console.log('<span style="display: none;">' + rescId + '</span>Car Name: ' + carName + ', Longitude: ' + lng + ', Latitude: ' + lat);

        rescuerMarker = L.marker([lat, lng], {
          icon: L.divIcon({
            className: 'custom-marker',
            html: '<div style="background-color: red;" class="marker-dot"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
          })
        }).addTo(map);

        // Initialize the array for each resc_id if not already done
        quantitiesAndNames[rescId] = quantitiesAndNames[rescId] || [];

        // Push quantity and name to the array
        quantitiesAndNames[rescId].push({ quantity: quantity, name: name });

        // Add click event listener to the marker
        rescuerMarker.on('click', function () {
          // Call your custom function here
          rescuerMarkerClick(rescId, lat, lng);
        });

        // Construct content for the bindPopup
        let content = '<b>Car Name: ' + carName + '</b><br>' +
                      '<b>Tasks: ' + currTask + '</b><br>';

        // Concatenate quantities and names for the current resc_id
        quantitiesAndNames[rescId].forEach(function (item) {
          content += '<b>Name: ' + item.name + '</b>' + ', ' +
          '<b>Quantity: ' + item.quantity + '</b><br>';
        });

        // Add the content to the marker's popup
        rescuerMarker.bindPopup(content).openPopup();
      });
    });
  }
};

xhr4.send();




requestsCoordinates();

        // Get requests coordinates from database
        function requestsCoordinates(){
          let xhr5 = new XMLHttpRequest();
xhr5.open('GET', 'includes/request-coordinates.inc.php', true);
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
            let color;

            let requestMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map);

            if (active == 0) {
                color = 'yellow';
                yellowMarkers.push(requestMarker);
            } else {
                color = 'blue';
            }

            // Set marker icon HTML based on color
            requestMarker.setIcon(L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: ' + color + ';" class="marker-dot"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            }));

            offerMarkersMap[taskID] = requestMarker;

            requestMarker.bindPopup('<span style="display: none;"><b>Task_id:' + taskID + '.</b></span>' + '<b>Name:' + name + '.</b><br><b>Lastname:' + lastname +
                '.</b><br><b>Phone:' + phone + '.</b><br><b>Publish Date:' + publishDate +
                '.</b><br><b>Item name:' + itemName + '.</b><br><b>Quantity:' + quantity +
                '.</b><br><b>Withdrawal Date:' + withdrawDate + '.</b><br><b>Car name:' + carName 
                ).openPopup();

            counter++;
        });

    }
};

xhr5.send();
        }

offersCoordinates();

// Get offers coordinates from database
function offersCoordinates(){
          let xhr6 = new XMLHttpRequest();
xhr6.open('GET', 'includes/offer-coordinates.inc.php', true);
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
            let color;

            let offerMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map);

            if (active == 0) {
                color = 'green';
                greenMarkers.push(offerMarker);
            } else {
                color = 'purple';
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

            
            offerMarkersMap[taskID] = offerMarker;


            offerMarker.bindPopup('<span style="display: none;"><b>Task_id:' + taskID + '.</b></span>' + '<b>Name:' + name + '.</b><br><b>Lastname:' + lastname +
                '.</b><br><b>Phone:' + phone + '.</b><br><b>Publish Date:' + publishDate +
                '.</b><br><b>Item name:' + itemName + '.</b><br><b>Quantity:' + quantity +
                '.</b><br><b>Withdrawal Date:' + withdrawDate + '.</b><br><b>Car name:' + carName
            ).openPopup();

            counter++;
        });

    }
    
};

xhr6.send();
        }




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

    // Function for rescuerMarker click
    function rescuerMarkerClick(rescId, lat, lng) {

      // Clear previously drawn polylines
      polylines.forEach(function(polyline) {
        map.removeLayer(polyline);
      });

      polylines = [];  // Empty the polylines array

      console.log("Marker clicked! Resc_id: " + rescId + ", Latitude: " + lat + ", Longitude: " + lng);
      // Select active tasks based on this id
      let xhr7 = new XMLHttpRequest();
      xhr7.open('POST', 'includes/resc_id-find-task_id.inc.php', true);
      xhr7.setRequestHeader('Content-type', 'application/json');

      xhr7.onload = function () {
          if (xhr7.status == 200) {
              let response = JSON.parse(this.responseText);
              // Take the task id, find what marker it belongs to and draw the lines between this rescuerMarker and those active tasks
              console.log(response);
              // Iterate over each task_id in the response
              response.data.forEach(function (task) {
                let taskId = task.task_id;

                // Retrieve the offerMarker associated with the task_id
                let offerMarker = offerMarkersMap[taskId];
                let requestMarker = requestMarkersMap[taskId];

                if (offerMarker) {
                    // Create a polyline between the rescuerMarker and the offerMarker
                    let polyline = L.polyline([
                        [lat, lng],
                        offerMarker.getLatLng()
                    ]).addTo(map);

                    // Store the polyline in the array
                    polylines.push(polyline);
                }

                if (requestMarker) {
                    // Create a polyline between the rescuerMarker and the offerMarker
                    let polyline = L.polyline([
                        [lat, lng],
                        requestMarker.getLatLng()
                    ]).addTo(map);

                    // Store the polyline in the array
                    polylines.push(polyline);
                }
              });
          }
        };
        console.log('resc_id: ', rescId);
        // Convert data to JSON format
        let jsonData = JSON.stringify({ resc_id: rescId });

        xhr7.send(jsonData);
    }

    // Function for not active requests
function toggleYellowMarkers() {
    yellowMarkers.forEach(function (marker) {
        if (map.hasLayer(marker)) {
            map.removeLayer(marker);
        } else {
            map.addLayer(marker);
        }
    });
}

// Function for not active offers
function toggleGreenMarkers() {
    greenMarkers.forEach(function (marker) {
        if (map.hasLayer(marker)) {
            map.removeLayer(marker);
        } else {
            map.addLayer(marker);
        }
    });
}




  </script>
  </div>

  <div class="flex-container" style="width: 100%; height:auto; border: none; padding: 0; display: flex; flex-direction: row;">
    <button id="toggleNotActiveRequests">Not active requests Toggle</button>
    <button id="toggleNotActiveOffers">Not active offers Toggle</button>
  </div>

  <div>
      <span style="color: red;">Rescuer: Red, </span>
      <span style="color: yellow;">Not active Request: Yellow, </span>
      <span style="color: blue;">Active Request: Blue, </span>
      <span style="color: green;">Not active Offer: Green, </span>
      <span style="color: purple;">Active Offer: Purple</span>
    </div>

  <div id="coordinates"></div>

  <div id='message'></div>

  <script>
    document.getElementById('toggleNotActiveOffers').addEventListener('click', toggleGreenMarkers);
document.getElementById('toggleNotActiveRequests').addEventListener('click', toggleYellowMarkers);
  </script>
    
<script src="main_admin.js"></script>
</body>
</html>