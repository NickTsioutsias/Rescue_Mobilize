<?php
  require "config.php";
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
  <title>Insert Items</title>
  <link rel="stylesheet" href="LoginCSS.css">
  <style>
    body{
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    p{
      color: red;
    }
    #category {
    padding: 8px;
    font-size: 14px;
    width: 200px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f8f8f8;
  }

  /* Style for the options within the select element */
  #category option {
    padding: 8px;
    font-size: 12px;
    color: #333;
    background-color: #fff;
  }

  /* Hover effect for options */
  #category option:hover {
    background-color: #e0e0e0;
  }

  /* Style for the selected option */
  #category option:checked {
    background-color: #ddd;
    font-weight: bold;
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
  </style>
</head>
<body>
<div id="nav_container">
  <nav class="navbar">
    <ul>
      <li><a href="main_admin.php">HOME.</a></li>
      <!-- Page for rescuer registration -->
      <li><a href="signup_rescuer.php">Create a Rescuer account.</a></li>
      <li><a href="insert_category.php">Insert categories.</a></li>
      <!-- Page for altering quantities of items in database -->
      <li><a href="item_quantity.php">Change quantity of items here.</a></li>
      <li><a href="announcements.php">Create announcements</a></li>
      <li><a href="inventory.php">Inventory</a></li>
      <li><a href="charts.php">Charts</a></li>
      <li>
        <form id="logout-form">
        <button type="submit" id="logout-button" name="logout-submit">Logout</button>
        </form>
      </li>
    </ul>
  </nav>
  </div>
<div class="login-container" style="margin-bottom: 200px;">
  <h2>Insert items!</h2>

  <form id="item-form">
    
    <div class="form-group">
      <label for="category">Category</label>
      <select id="category" name="category"></select> 
    </div>

    <div class="form-group">
      <label for="item">Item</label>
      <input type="text" id="item" name="item" disabled required>   
    </div>
    
    <button type="submit" name="submit-button" id="submit-button">Submit</button>
    
  </form>

  <p id="message" class="error-message"></p>
  
</div>
<script src="insert_item.js"></script>
</body>
</html>