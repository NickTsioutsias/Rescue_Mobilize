<?php
  require "config.php";
?>

if

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
      <button type="submit" id="submit-button">Login</button>
    </form>
    
    <a href="signup.php">Sign up!</a>
    
    <form action="includes/logout.php" method="post" id="login-form">
      <button type="submit" id="logout-button" name="logout-submit">Logout</button>
    </form>
      
    <p id="error-message" class="error-message"></p>
  </div>

  <script src="script.js"></script>

</body>
</html>

