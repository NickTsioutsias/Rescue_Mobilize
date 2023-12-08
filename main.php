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
  
  if(strpos($fullUrl, "error=sqlerror") == true){
    echo "There was an SQL error";
  }
  elseif(strpos($fullUrl, "signup=success") == true){
    echo "Hooray! You Signed up!";
  }

?>
</body>
</html>