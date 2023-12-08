<?php
  require "config.php";
  // Error Handling
  $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  if($_SESSION['role'] != 'admin'){
    header("Location: main.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <link rel="stylesheet" href="LoginCSS.css">
  <style>
    p{
      color: red;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Sign up!</h2>

    <form action="includes/register_rescuer.php" method="post" id="login-form">
      
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>

      <?php
          if(strpos($fullUrl, "signup=wrongusername") == true){
            echo '<p>Username needs to have alphanumeric characters and _ symbols only, up to 20 characters</p>';
          }
      ?>
    
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
    
      <?php
          if(strpos($fullUrl, "signup=invalidpassword") == true){
            echo '<p>Password needs to have alphanumeric characters and ! or _ symbols only</p>';
          }
      ?>

      <div class="form-group">
        <label>Name</label>
        <input type="text" id="name" name="name" required>
      </div>
      
      <?php
          if(strpos($fullUrl, "signup=invalidname") == true){
            echo '<p>Names can only be written in lower and uppercase letters</p>';
          }
      ?>

      <div class="form-group">
        <label>Lastname</label>
        <input type="text" id="lastname" name="lastname" required>
      </div>
      
      <?php
        if(strpos($fullUrl, "signup=invalidlastname") == true){
          echo '<p>Lastnames can only be written in lower and uppercase letters</p>';
        }
      ?>

      <div class="form-group">
        <label>Car name</label>
        <input type="text" id="car_name" name="car_name" required>
      </div>

      <?php
          if(strpos($fullUrl, "signup=invalidcarname") == true){
            echo '<p>Invalid car name</p>';
          }
      ?>
            
      <div class="form-group">
        <label for="latitude">Latitude:</label>
        <input type="text" name="latitude" required>
      </div>

      <?php
          if(strpos($fullUrl, "signup=invalidlatitude") == true){
            echo '<p>Latitude should be like: 38.246229</p>';
          }
      ?>
      
      <div> 
        <label for="longitude">Longitude:</label>
        <input type="text" name="longitude" required>
      </div>

      <?php
          if(strpos($fullUrl, "signup=invalidlongtitude") == true){
            echo '<p>Latitude should be like: 21.735412</p>';
          }
      ?>
      
      <button type="submit" name="signup-submit" id="submit-button">Register</button>
      
    </form>
    
    <p id="error-message" class="error-message"></p>
  
  </div>

  <script src="script.js"></script>

</body>
</html>



