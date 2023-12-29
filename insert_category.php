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
  </style>
</head>
<body>
<div class="login-container">
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