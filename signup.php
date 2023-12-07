<?php
  require "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <link rel="stylesheet" href="LoginCSS.css">
</head>
<body>

  <div class="login-container">
    <h2>Sign up!</h2>

    <form action="includes/register_citizen.php" method="post" id="login-form">
      
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">
      </div>
    
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
      </div>
    
      <div class="form-group">
        <label>Name</label>
        <input type="text" id="name" name="name">
      </div>
      
      <div class="form-group">
        <label>Lastname</label>
        <input type="text" id="lastname" name="lastname">
      </div>
      
      <div class="form-group">
        <label>Phone</label>
        <input type="text" id="phone" name="phone">
      </div>
      
      <div class="form-group">
        <label>Country</label>
        <input type="text" id="country" name="country">
      </div>
      
      <div class="form-group">
        <label>City</label>
        <input type="text" id="city" name="city">
      </div>
      
      <div class="form-group">
        <label>Address</label>
        <input type="text" id="address" name="address">
      </div>
      
      <div class="form-group">
        <label>Zip</label>
        <input type="text" id="zip" name="zip">
      </div>

      <button type="submit" name="signup-submit" id="submit-button" onclick="FieldsTest()">Register</button>
      
    </form>
    
    <p id="error-message" class="error-message"></p>
  
  </div>

  <script src="SignU.js"></script>

</body>
</html>



