<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    body {
      margin: 0; /* Remove default margin */
      font-family: Arial, Helvetica, sans-serif;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }

    .announcements1 {
      max-height: 33vh;
      overflow: auto;
    }

    .history {
      border-collapse: collapse;
      width: 100%;
    }

    .history td{
      border: 1px solid #ddd;
      padding: 8px;
      width: 50%;
    }
    .history th{
      border: 1px solid #ddd;
      padding: 8px;
      width: 40%;
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
      <li><a href="announcements.php">Create announcements</a></li>
      <li><a href="charts.php">Charts</a></li>
      <li>
        <form id="logout-form">
        <button type="submit" id="logout-button" name="logout-submit">Logout</button>
        </form>
      </li>
    </ul>
  </nav>
  </div>

  <h2>Base inventory</h2>
  <table class="history">
    <tr>
      <th>Item</th>
      <th style="width:41%;">Quantity</th>
    </tr>
  </table>
  
  <div class="announcements1">
    <table id="base-inventory" class="history">
      <tr>
        <td></td>
        <td></td>
      </tr>
    </table>
  </div>

  <div id="car-inventory"></div>

  

  <script src="inventory.js"></script>
</body>
</html>
