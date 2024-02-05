<?php
  require "database.php";
  require "config.php";
  if($_SESSION['role'] != 'citizen'){
    header("Location: login.php");
    require "includes/logout.php";
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
    /* Change button style on hover */
    .button:hover {
      background-color: #45a049; /* Darker green on hover */
    }
    .flex-item {
      flex: 1;
      padding: 20px;
      text-align: center;
    }
    .flex-container {
      display: flex;
      flex-direction: column;
      width: 100%;
      align-items: center;
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
    #category, #item{
      padding: 8px;
    font-size: 14px;
    width: 200px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f8f8f8;
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
  </style>
</head>
<body>

  <div id="nav_container">
    <nav class="navbar">
      <ul>
        <li><a href="request.php">Create a request.</a></li>
        <li>
          <form id="logout-form">
          <button type="submit" id="logout-button" name="logout-submit">Logout</button>
        </form>
        </li>
      </ul>
    </nav>
  </div>

  <div class="flex-container" id="announcements">
    <div>
      <h2>Announcements history</h2>
    </div>

    <div class="flex-container">
      <table id="announcements-history" class="history">
        <tr>
          <th>Description</th>
          <th>Category</th>
          <th>Item</th>
          <th>Quantity</th>
        </tr>
      </table>
    </div>    
  </div>

  <div class="flex-container" id="requests">
    <div>
      <h2>Requests History</h2>    
    </div>

    <div class="flex-container">
      <table id="requests-history" class="history">
        <tr>
          <th>Item name</th>
          <th>People</th>
          <th>Date</th>
          <th>Situation</th>
        </tr>
      </table>
    </div>   
  </div>

  <div class="flex-container" id="offers">
    <div>
      <h2>Offers History</h2>     
    </div> 
    
    <table id="offers-history" class="history"></table>
  </div>
  <div id="message"></div>

  <script src="main_citizen.js"></script>



</body>
</html>