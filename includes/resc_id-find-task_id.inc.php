<?php

require "../database.php";
require "../config.php";

// Retrieve JSON data from the request body
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

// Check if 'resc_id' is set in the JSON data
if (isset($data['resc_id'])) {
  $resc_id = $data['resc_id'];

    $sql = "SELECT task_id FROM task WHERE resc_id = ? AND active = true;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['success' => false, 'message' => 'SQL error']);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $resc_id);
        mysqli_stmt_execute($stmt);

        // Fetch all rows from the result set as an associative array
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Always provide a valid JSON response
        echo json_encode(['success' => true, 'data' => $rows], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
} else {
    // If 'resc_id' is not set, provide a valid JSON response
    echo json_encode(['success' => false, 'message' => 'No resc_id provided in the request.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
