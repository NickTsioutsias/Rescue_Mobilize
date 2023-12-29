<?php
  require "database.php";

  $json_url = "http://usidas.ceid.upatras.gr/web/2023/export.php";

  $json_data = file_get_contents($json_url);

  if ($json_data === FALSE) {
    die("Error fetching JSON data from the URL");
  }
  $data = json_decode($json_data, true);

  // Categories
  foreach ($data["categories"] as $category) {
    $categId = $category["id"];
    $categName = $category["category_name"];

    $sql = "INSERT INTO category (categ_id, categ_name) VALUES (?, ?)";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: usidas_json.php?error=sqlerror0");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt, "is", $categId, $categName);
      // Execute the statement inside the database
      mysqli_stmt_execute($stmt);
    }
  }
  // Items
  foreach ($data["items"] as $item) {
    $itemId = $item["id"];
    $itemName = $item["name"];
    $categoryId = $item["category"];

    $sql = "INSERT INTO inventory (id, name, categ_id) VALUES (?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: usidas_json.php?error=sqlerror1");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt, "isi", $itemId, $itemName, $categoryId);
      // Execute the statement inside the database
      mysqli_stmt_execute($stmt);
    }
  }

// Close the connection
$conn->close();

?>