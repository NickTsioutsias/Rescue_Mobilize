<?php
  require "database.php";
  require "config.php";
  if($_SESSION['role'] != 'citizen'){
    header("Location: index.php");
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
  <style>
     /* Style the button to make it look like a button */
     .button {
      display: inline-block;
      padding: 10px 20px;
      font-size: 16px;
      text-align: center;
      text-decoration: none;
      background-color: #4CAF50; /* Green background color */
      color: #fff; /* White text color */
      border: 1px solid #4CAF50; /* Green border */
      border-radius: 5px; /* Rounded corners */
      cursor: pointer;
    }

    /* Change button style on hover */
    .button:hover {
      background-color: #45a049; /* Darker green on hover */
    }
  </style>
</head>
<body>
    <a href="request.php" class="button">Requests</a>
    <a href="offer.php" class="button">Offers</a>
    <form id="logout-form">
      <button type="submit" id="logout-button" name="logout-submit">Logout</button>
    </form>
    <script src="main_admin.js"></script>
</body>
</html>