<?php
  require "config.php";
  
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
    <h2>Login</h2>
    <form action="includes/adminlogin.inc.php" method="post" id="login-form">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <?php
        if(strpos($fullUrl, "error=wrongpwd") == true){
          echo '<p>Wrong password!</p>';
        }
      ?>
      <button type="submit" id="submit-button">Login</button>
    </form>

    <?php
      if(strpos($fullUrl, "error=noadminuser") == true){
        echo '<p>There is no admin like that!</p>';
      }
    ?>
          
    <p id="error-message" class="error-message"></p>
  </div>

  <script src="script.js"></script>

</body>
</html>

