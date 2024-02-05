<?php
require "../database.php";
require "../config.php";

$user_id = $_SESSION['user_id'];
$jsonData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($jsonData, true);
$task_id = $data['task_id'];

// If active = 1 => curr_task -1 to that rescuer

$sql = "SELECT * FROM task WHERE task_id=?;";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    $response = ['success' => false, 'message' => 'SQL error'];
} else {
    mysqli_stmt_bind_param($stmt, "i", $task_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if($row['active'] == 1){
      $sql = "UPDATE rescuer 
      SET curr_task = curr_task - 1 WHERE resc_id = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)) {
          $response = ['success' => false, 'message' => 'SQL error'];
      } else {
          mysqli_stmt_bind_param($stmt, "i", $row['resc_id']);
          mysqli_stmt_execute($stmt);
          // Delete row from database
          $sql ="DELETE FROM task WHERE task_id = ?;";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            $response = ['success' => false, 'message' => 'SQL error'];
        } else {
            mysqli_stmt_bind_param($stmt, "i", $task_id);
            mysqli_stmt_execute($stmt);
            header('Content-Type: application/json');
            $response = ['success' => true, 'message' => 'Successful cancelation', 'redirect' => 'main_citizen.php'];
            echo json_encode($response);
      }    
    }
  }
  else{
    $sql ="DELETE FROM task WHERE task_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      $response = ['success' => false, 'message' => 'SQL error'];
  } else {
      mysqli_stmt_bind_param($stmt, "i", $task_id);
      mysqli_stmt_execute($stmt);
      header('Content-Type: application/json');
      $response = ['success' => true, 'message' => 'Successful cancelation', 'redirect' => 'main_citizen.php'];
      echo json_encode($response);
}    
  }
}
        // Close the database connection
        mysqli_close($conn);

        // Set the content type header to JSON

        // Send the JSON response
    




