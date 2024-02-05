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
  <title>Item quantity</title>
  <link rel="stylesheet" href="LoginCSS.css">
  <style>
  #autocomplete-results {
    border: 1px solid #ccc;
    max-height: 150px;
    overflow-y: auto;
    position: absolute;
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
</style>

</head>
<body>
  <div class="login-container">
    <form id="quantity-form">
      <h2>Change quantity of items!</h2>

      <div style="display: flex; flex-direction:row;">
      <button type="submit" id="load-button" name="load-button" style="margin-right: 5px;">Load</button>
      </div>
      


      <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" min="1"  autocomplete="off" required>
      </div>

      <div class="form-group" style="margin-bottom: 0;">
        <label for="autocomplete">Item</label>
        <input type="text" id="autocomplete" name="autocomplete" autocomplete="off" required>
      </div>

      <div id="autocomplete-results"></div>    
      <div id="message" class="error-message"></div>
  </form>

  </div>

<script src="load.js"></script>
</body>
</html>