<?php
require "../database.php";
require "../config.php";

$user_id = $_SESSION['user_id'];
$jsonData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($jsonData, true);
$task_id = $data['task_id'];


        $sql = "UPDATE rescuer 
        SET curr_task = curr_task - 1 WHERE resc_id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $response = ['success' => false, 'message' => 'SQL error'];
        } else {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
        }

        $sql = "UPDATE task
        SET resc_id = NULL, withdraw_date = NULL, active = FALSE
        WHERE task_id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $response = ['success' => false, 'message' => 'SQL error'];
        } else {
            mysqli_stmt_bind_param($stmt, "i", $task_id);
            mysqli_stmt_execute($stmt);
            $response = ['success' => true, 'message' => 'Successful cancelation', 'redirect' => 'main_rescuer.php'];
        }

        // Close the database connection
        mysqli_close($conn);

        // Set the content type header to JSON
        header('Content-Type: application/json');

        // Send the JSON response
        echo json_encode($response);
    




