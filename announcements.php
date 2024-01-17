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
  <title>Create Announcements</title>
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
    input[type="text"],
    input[type="password"] {
      width: calc(100% - 10px);
      padding: 5px;
      border-radius: 3px;
      border: 1px solid #ccc;
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
  .anouncements1{
    display: flex;
    width: 100%;
  }
  .flex-item {
    flex: 1;
    padding: 20px;
    text-align: center;
  }
  .flex-container {
    display: flex;
    justify-content: flex-end; 
    align-items: center;
    padding: 20px;
    margin-left: 70%;
    }
    #anouncement_form {
      display: flex;
      flex-direction: column;
    }

    #anouncement_form .flex-container {
      justify-content: space-between; /* Adjust as needed */
    }
    #announcements-history {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    #announcements-history td, #announcements-history th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    #announcements-history tr:nth-child(even){background-color: #f2f2f2;}

    #announcements-history tr:hover {background-color: #ddd;}

    #announcements-history th {
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

  </style>
</head>
<body>
  <div id="nav_container">
  <nav class="navbar">
    <ul>
      <li><a href="main_admin.php">Home.</a></li>
      <!-- Page for rescuer registration -->
      <li><a href="signup_rescuer.php">Create a Rescuer account.</a></li>
        <!-- Page for adding new categories in database -->
      <li><a href="insert_category.php">Insert categories.</a></li>
      <!--  Page for adding new items in database -->
      <li><a href="insert_item.php">Insert items.</a></li>
      <!-- Page for altering quantities of items in database -->
      <li><a href="item_quantity.php">Change quantity of items here.</a></li>
      <li>
        <form id="logout-form">
        <button type="submit" id="logout-button" name="logout-submit">Logout</button>
        </form>
      </li>
    </ul>
  </nav>
  </div>
  
  <form id="anouncement_form">

  <div class="anouncements1">

    <div class="flex-item" style="flex-direction: column;">
      <div>

      </div>
        <label for="description">Description</label>
      <div>
        <textarea id="description" name="description" form="anouncement_form" style="resize: none;" rows="4" cols="50" required></textarea>
      </div>
    </div>


    <div class="flex-item">
      <div class="flex-item">
        <div>
          <label for="category">Category</label>
        </div>
        <div>
          <select id="category" name="category"></select> 
        </div>
      </div>

      <div class="flex-item">
        <div>
            <label for="item">Item</label>
          </div>
          <div>
            <select id="item" name="item" disabled><option>Choose an item</option></select> 
            
        </div>
      </div>

    </div>

    <div class="flex-item">
      <div>
        <label for="quantity">Quantity</label>
      </div>
      <div>
        <input type="number" id="quantity" name="quantity" min="1" autocomplete="off">
      </div>
    </div>
  
  </div>

  <div class="flex-container">

   <div class="flex-item">
      <button type="submit">Confirm</button>
    </div>

    <div class="flex-item">
      <button type="reset">Clear</button>
   </div>

  </div>
  <p id="message"></p>

  </form>

  <div class="anouncements1">
    <table id="announcements-history">
      <tr>
        <th>Description</th>
        <th>Category</th>
        <th>Item</th>
        <th>Quantity</th>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </div>

  <script src="announcements.js"></script>

</body>
</html>