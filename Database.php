<?php

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "rescue_mobilize";
    $conn = "";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    /* try{
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
     }

    catch(mysqli_sql_exception){
        echo"Could not connect!";
    }

    if($conn){
        echo"You are connected!";
    }

    if($conn){
        echo"You are connected!";
    }
    */
?>