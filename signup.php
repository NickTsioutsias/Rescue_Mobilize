<?php
  require "config.php";
?>

<?php
  // Error Handling
  $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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

    <form action="includes/register_citizen.php" method="post" id="login-form">
      
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
        <label>Phone</label>
        <input type="text" id="phone" name="phone" required>
      </div>
        
      <?php
        if(strpos($fullUrl, "signup=invalidphone") == true){
          echo '<p>This is not a phone number</p>';
        }
      ?>    

      <div class="form-group">
        <label>Country</label>
        <input type="text" id="country" name="country" required>
      </div>
      
      <?php
        if(strpos($fullUrl, "signup=invalidcountry") == true){
          echo '<p>This is not a country</p>';
        }
      ?>

      <div class="form-group">
        <label>City</label>
        <input type="text" id="city" name="city" required>
      </div>
        
      <?php
        if(strpos($fullUrl, "signup=invalidcity") == true){
          echo '<p>This is not a city</p>';
        }
      ?>    

      <div class="form-group">
        <label>Address</label>
        <input type="text" id="address" name="address" required>
      </div>
        
      <?php
        if(strpos($fullUrl, "signup=invalidaddress") == true){
          echo '<p>This is not an address</p>';
        }
      ?>    

      <div class="form-group">
        <label>Zip</label>
        <input type="text" id="zip" name="zip" required>
      </div>

      <?php
        if(strpos($fullUrl, "signup=invalidzip") == true){
          echo '<p>This is not a zip code</p>';
        }
      ?>    

      <button type="submit" name="signup-submit" id="submit-button" onclick="FieldsTest()">Register</button>
      
    </form>
    
    <?php
        if(strpos($fullUrl, "error=sqlerror") == true){
          echo '<p>There was an SQL error</p>';
        }
        elseif(strpos($fullUrl, "signup=empty") == true){
          echo '<p>You did not fill in all fields!</p>';
        }
        elseif(strpos($fullUrl, "error=usernamephonetaken") == true){
          echo '<p>This username is taken, or phone number already exists</p>';
        }
    ?>


    <p id="error-message" class="error-message"></p>
  
  </div>

  <script src="SignUpJS.js"></script>

</body>
</html>



