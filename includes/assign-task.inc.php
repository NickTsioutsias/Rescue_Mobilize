<?php
require "../database.php";
require "../config.php";

$user_id = $_SESSION['user_id'];
$task_id = $_POST['task_id'];

$sql = "SELECT curr_task FROM rescuer WHERE resc_id = ?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $response = ['success' => false, 'message' => 'SQL error'];
} else {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $result);
    mysqli_stmt_fetch($stmt);

    if ($result >= 4){
        $response = ['success' => false, 'message' => 'You cannot have more than 4 tasks'];
        // Set the content type header to JSON
        header('Content-Type: application/json');

        // Send the JSON response
        echo json_encode($response);
    }
    else{
        $sql = "UPDATE rescuer 
        SET curr_task = curr_task + 1 WHERE resc_id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $response = ['success' => false, 'message' => 'SQL error'];
        } else {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
        }

        $sql = "UPDATE task
        SET resc_id = ?, withdraw_date = NOW(), active = TRUE
        WHERE task_id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $response = ['success' => false, 'message' => 'SQL error'];
        } else {
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $task_id);
            mysqli_stmt_execute($stmt);
            $response = ['success' => true, 'message' => 'Successful assignment', 'redirect' => 'main_rescuer.php'];
        }

        // Close the database connection
        mysqli_close($conn);

        // Set the content type header to JSON
        header('Content-Type: application/json');

        // Send the JSON response
        echo json_encode($response);
    }
}



