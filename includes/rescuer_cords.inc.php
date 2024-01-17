<?php
  require "../database.php";
  require "../config.php";

  $user_id = $_SESSION['user_id'];

  $sql = "SELECT resc_id, curr_task, ST_X(r_cords) as lng, ST_Y(r_cords) as lat, car_name FROM rescuer
  WHERE resc_id = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(['success' => false, 'message' => 'SQL error']);
 }
 else {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    // Fetch a single row from the result set as an associative array
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($row) {
        // Encode the single row into JSON data
        $encoded_data = json_encode(['success' => true, 'data' => $row], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo $encoded_data;
    } else {
        echo json_encode(['success' => false, 'message' => 'No data found for the user.']);
    }
}