<?php
require "../database.php";
if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
  
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];

  $sql = "UPDATE base SET base_cords = ST_GeomFromText(?)";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(['success' => false, 'message' => 'SQL error']);
  }
  else {
    $wktPoint = "POINT($longitude $latitude)";
    mysqli_stmt_bind_param($stmt, "s", $wktPoint);
    mysqli_stmt_execute($stmt);
    echo json_encode(['success' => true]);
  }
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}