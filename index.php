<?php
  require "config.php";
  require "database.php";
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
    <h2>Login</h2>
    <form action="includes/login.inc.php" method="post" id="login-form">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
      </div>
      <?php
        // Error Handling
        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if(strpos($fullUrl, "error=wrongpwd") == true){
          echo '<p> Wrong password</p>';
        }
        elseif(strpos($fullUrl, "error=nouser") == true){
          echo '<p> There is no user with this username</p>';
        }
      ?>
      <button type="submit" id="submit-button" onclick="FieldsTest()">Login</button>
    </form>

    <?php
        if(strpos($fullUrl, "error=sqlerror") == true){
          echo '<p>There was an SQL error</p>';
        }
    ?>
    
    <a href="signup.php">Sign up!</a>
      
    <p id="error-message" class="error-message"></p>
  </div>

  <script src="LoginJS.js"></script>
  
</body>
</html>