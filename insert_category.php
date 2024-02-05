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
  <title>Insert categories</title>
  <link rel="stylesheet" href="LoginCSS.css">
  <style>
    p{
      color: red;
    }
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
  </style>
</head>
<body>

<div id="nav_container">
  <nav class="navbar">
    <ul>
      <li><a href="main_admin.php">HOME.</a></li>
      <!-- Page for rescuer registration -->
      <li><a href="signup_rescuer.php">Create a Rescuer account.</a></li>
      <!--  Page for adding new items in database -->
      <li><a href="insert_item.php">Insert items.</a></li>
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
<div class="login-container" style="margin-top: 10%;">
    <h2>Add category!</h2>

    <form id="category-form">
      
      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" id="category" name="category" required>
      </div>
      
      <button type="submit" name="submit" id="submit-button">Register</button>
      
    </form>

    <p id="message" class="error-message"></p>
  
</div>
<script src="insert_category.js"></script>
</body>
</html>