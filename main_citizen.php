<?php
    if($_SESSION['role'] != 'citizen'){
      header("Location: index.php");
      require "includes/logout.php";
      exit();
    }
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    
</body>
</html>