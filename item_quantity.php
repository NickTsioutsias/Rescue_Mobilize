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
  <title>Item quantity</title>
  <link rel="stylesheet" href="LoginCSS.css">
  <style>
    body{
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
  #autocomplete-results {
    border: 1px solid #ccc;
    max-height: 150px;
    overflow-y: auto;
    position: absolute;
    margin-top: 10px; /* Add some margin to the top */
    z-index: 1; /* Ensure that the results are rendered below the button */
    width: 100%; /* Ensure full width */
  }

  .result {
    padding: 8px;
    cursor: pointer;
  }

  .result:hover {
    background-color: #ddd;
  }

  #quantity-form {
    position: relative; /* Set the position to relative */
  }

  #submit-button {
    margin-top: 10px; /* Add some margin to the top */
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
        <!-- Page for adding new categories in database -->
      <li><a href="insert_category.php">Insert categories.</a></li>
      <!--  Page for adding new items in database -->
      <li><a href="insert_item.php">Insert items.</a></li>
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
    <form id="quantity-form">
      <h2>Change quantity of items!</h2>

      <button type="submit" id="submit-button" name="submit-button">Submit</button>

      <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" autocomplete="off" min="1" required>
      </div>

      <div class="form-group">
        <label for="item">Item</label>
        <input type="text" id="autocomplete" name="autocomplete" autocomplete="off" required>
      </div>

      <div id="autocomplete-results"></div>    
      <div id="message" class="error-message"></div>
  </form>

  </div>

<script src="quantity.js"></script>
</body>
</html>