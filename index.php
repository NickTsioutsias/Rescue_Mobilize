<?
    include("database.php");
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
    <form id="login-form">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit">Login</button>
      <button type="signup">Sign Up</button>
    </form>
    <p id="error-message" class="error-message"></p>
  </div>

  <script src="script.js"></script>
</body>
</html>