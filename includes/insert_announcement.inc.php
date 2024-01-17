<?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require "../database.php";

    // Create variables of submited items
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $selectedText = $_POST['selectedText'];
    $selectedText2 = $_POST['selectedText2'];
      
      // Sanitization techniques: filtering malicious script
      $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);

      // Create announcement
      $sql = "INSERT INTO announcements (description, quantity, categ_name, item_name) VALUES (?, ?, ?, ?);";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(['success' => false, 'message' => 'There was an SQL error']);
        exit();
      }
      else{
        mysqli_stmt_bind_param($stmt, "siss", $description, $quantity, $selectedText, $selectedText2);
        mysqli_stmt_execute($stmt);
      }
      echo json_encode(['success' => true, 'message' => 'Success', 'redirect' => 'main_admin.php']);
  }
  mysqli_close($conn);

      