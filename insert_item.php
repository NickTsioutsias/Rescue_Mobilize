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
  </style>
</head>
<body>
<div class="login-container">
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