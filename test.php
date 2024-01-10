<?php
  // In this file we convert Rescuer data to a JSON file
  require "database.php";

  // SQL prepared statement for selecting all users data with role = rescuer
  $sql = "CALL getdata()";
  
  $username = array();
  $name = array();
  $lastname = array();
  $curr_task = array();
  $r_cords = array();
  $car_name = array();

  $select = mysqli_query($conn,$GLOBALS['sql']);

  if(mysqli_num_rows($select) ){
      
      while($row = mysqli_fetch_assoc($select)){
          
          $GLOBALS['username'][] = $row["username"];
          $GLOBALS['name'][] = $row["name"];
          $GLOBALS['lastname'][] = $row["lastname"];
          $GLOBALS['curr_task'][] = $row["curr_task"];
          $GLOBALS['r_cords'][] = $row["r_cords"];
          $GLOBALS['car_name'][] = $row["car_name"];
      }
  }
   
  print_r($username);
  print_r($name);
  print_r($lastname);
  print_r($curr_task);
  print_r($r_cords);
  print_r($car_name);
      
  