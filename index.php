<?php
  require "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>

  <?php
  // If we are logged in we can do stuff here
    if (isset($_SESSION['user_id'])) {
      echo '<p class="login-status">You are logged in!</p>';
      echo '<form action="includes/logout.php" method="post" id="login-form">
      <button type="submit" id="logout-button" name="logout-submit">Logout</button>
      </form>';
    }
    else {
      // If we are logged out different stuff happen in here
      echo '<p class="login-status">You are logged out.</p>';
    }
  ?>  
  
<?php
  // If we are not logged in we will be able to see the login url 
  if (!isset($_SESSION['user_id'])) {
    echo '<a href="login.php">Login</a> <br>';  
    echo '<a href="signup.php">Signup</a>';
  }
  else {
    // If we are we cannot see the login url
    echo '<a href="signup.php">Signup</a>';
  }
?>

<?php
  $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

  if(strpos($fullUrl, "signup=empty") == true){
    echo "You did not fill in all fields!";
  }
  elseif(strpos($fullUrl, "signup=wrongusername") == true){
    echo "Username needs to have alphanumeric characters and _ symbols only, up to 20 characters";
  }
  elseif(strpos($fullUrl, "signup=invalidpassword") == true){
    echo "Password needs to have alphanumeric characters and ! or _ symbols only";
  }
  elseif(strpos($fullUrl, "signup=invalidname") == true){
    echo "Names can only be written in lower and uppercase letters";
  }
  elseif(strpos($fullUrl, "signup=invalidlastname") == true){
    echo "Lastnames can only be written in lower and uppercase letters";
  }
  elseif(strpos($fullUrl, "signup=invalidphone") == true){
    echo "This is not a phone number";
  }
  elseif(strpos($fullUrl, "signup=invalidemail") == true){
    echo "Invalid email";
  }
  elseif(strpos($fullUrl, "signup=invalidaddress") == true){
    echo "Addresses can only be written in uppercase letters";
  }
  elseif(strpos($fullUrl, "signup=invalidzip") == true){
    echo "Invalid zip postal code";
  }
  elseif(strpos($fullUrl, "signup=invalidlatitude") == true){
    echo "Latitude is written like this: 38.246229";
  }
  elseif(strpos($fullUrl, "signup=invalidlongtitude") == true){
    echo "Longtitude is written like this: 21.735412";
  }
  elseif(strpos($fullUrl, "error=sqlerror") == true){
    echo "There was an SQL error";
  }
  elseif(strpos($fullUrl, "error=usernametaken") == true){
    echo "This username is taken, or phone number, email already exists";
  }
  elseif(strpos($fullUrl, "signup=success") == true){
    echo "Hooray! You Signed up!";
  }
  elseif(strpos($fullUrl, "login=empty") == true){
    echo "Please fill in all fields";
  }
  elseif(strpos($fullUrl, "error=wrongpwd") == true){
    echo "Wrong Password";
  }
  elseif(strpos($fullUrl, "login=success") == true){
    echo "Hooray! You Logged in!";
  }
  elseif(strpos($fullUrl, "error=nouser") == true){
    echo "There is no user with this username";
  }
?>



</body>
</html>