<?php
  require "config.php";
  if($_SESSION['role'] != 'citizen'){
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="LoginCSS.css">
  <style>
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
</style>

</head>
<body>
  <div class="login-container">
    <form id="quantity-form">
      <h2>Create Requests</h2>

      <button type="submit" id="submit-button" name="submit-button">Submit</button>

      <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" autocomplete="off" required>
      </div>

      <div class="form-group">
        <label for="item">Item</label>
        <input type="text" id="autocomplete" name="autocomplete" autocomplete="off" required>
      </div>

      <div id="autocomplete-results"></div>    
      <div id="message" class="error-message"></div>
  </form>

  </div>

<script src="request.js"></script>
</body>
</html>