<?php
require "../database.php";
require "../config.php";

$sql = "SELECT name, quantity FROM inventory;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $response = ['success' => false, 'message' => 'SQL error'];
} else {
    mysqli_stmt_execute($stmt);
    // Fetch the results and store them in an associative array
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Output the data as JSON
    echo json_encode($rows);

}
?>