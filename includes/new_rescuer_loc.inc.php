<?php
require "../database.php";
require "../config.php";
$user_id = $_SESSION['user_id'];

if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
  
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];

  $sql = "UPDATE rescuer SET r_cords = ST_GeomFromText(?)
  WHERE resc_id = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(['success' => false, 'message' => 'SQL error']);
  }
  else {
    $wktPoint = "POINT($longitude $latitude)";
    mysqli_stmt_bind_param($stmt, "si", $wktPoint, $user_id);
    mysqli_stmt_execute($stmt);
    echo json_encode(['success' => true]);
  }
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}