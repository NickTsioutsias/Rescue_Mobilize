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

    <form action="includes/register_rescuer.php" method="post" id="login-form">
      
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
        <label>Car name</label>
        <input type="text" id="car_name" name="car_name">
      </div>
            
      <div class="form-group">
        <label for="latitude">Latitude:</label>
        <input type="text" name="latitude">
      </div>
      
      <div> 
        <label for="longitude">Longitude:</label>
        <input type="text" name="longitude">
      </div>
      
      <button type="submit" name="signup-submit" id="submit-button">Register</button>
      
    </form>
    
    <p id="error-message" class="error-message"></p>
  
  </div>

  <script src="script.js"></script>

</body>
</html>



