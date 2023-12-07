<?php
  require "database.php";
  require "config.php";
?>


<?php
  // If we are logged in we can do stuff here
    if (isset($_SESSION['user_id'])) {
      echo '<!DOCTYPE html>
            <html lang="en">
            <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Document</title>
              <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
                integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
                crossorigin=""/>
              
            </head>
            <body>';
      echo '<p class="login-status">You are logged in!</p>';
      echo '<form action="includes/logout.php" method="post" id="login-form">
      <button type="submit" id="logout-button" name="logout-submit">Logout</button>
      </form>';
      // Create map for the user
      echo '<div id="map">
        </div>';

      // Check if logged in user is an Admin. In this if statement we can make the admin user experience
      if($_SESSION['role'] == 'admin'){
        // Page for rescuer registration
        echo '<a href="signuprescuer.php">Create a Rescuer account.</a><br>';
        // Page for adding new categories in database
        echo '<a href="insert_category.php">Insert categories.</a><br>';
        // Page for adding new items in database
        echo '<a href="insert_item.php">Insert items.</a><br>';
        // Page for altering quantities of items in database
        echo '<a href="item_quantity.php">Change quantity of items here.</a><br>';
      }
      
      // Check if logged in user is a rescuer. In this if statement we can make the rescuer user experience      
      if($_SESSION['role'] == 'rescuer'){
        // Rescuer does things here
      }
      
      // Check if logged in user is a citizen. In this if statement we can make the citizen user experience
      if($_SESSION['role'] == 'citizen'){
        // Citizen things happen in here
      }
    }
    else {
      // If we are logged out different stuff happen in here  
    }
  ?>  
  
<?php
  // If we are not logged in stuff happens here
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
    
  }
  else {
    // If we are, we cannot see the login url
    // echo '<a href="signup.php">Signup</a>';
  }
?>

<?php
  // Error Handling
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

  elseif(strpos($fullUrl, "error=wrongpwd") == true){
    echo "Wrong Password";
  }
  elseif(strpos($fullUrl, "login=success") == true){
    echo "Hooray! You Logged in!";
  }
  elseif(strpos($fullUrl, "error=nouser") == true){
    echo "There is no user with this username";
  }
  elseif(strpos($fullUrl, "error=noadminuser") == true){
    echo "There is no admin like that";
  }
  elseif(strpos($fullUrl, "signup=invalidcarname") == true){
    echo "Invalid car name";
  }
?>
</body>
</html>