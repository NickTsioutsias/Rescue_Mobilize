<?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    require "database.php";

    // Create variables of submitted items
    $category = $_POST['category'];
    $prod_name = $_POST['product'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    // Check if values were empty
  }