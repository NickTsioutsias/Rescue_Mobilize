<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "../database.php";
    require "../config.php";

    $data = json_decode(file_get_contents("php://input"), true);
    $task_id = filter_var($data['task_id'], FILTER_SANITIZE_SPECIAL_CHARS);

    // Retrieve user_id from the session
    $user_id = $_SESSION['user_id'];
    // select item name and quantity from the task id
    $sql = "SELECT inventory.name AS inventory_item_name, task.quantity
            FROM task
            INNER JOIN inventory ON task.id = inventory.id
            WHERE task.task_id = ?;";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'SQL error']);
      exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $task_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $itemName, $quantity);
        mysqli_stmt_fetch($stmt);

        if ($itemName === null) {
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => 'Task not found']);
          exit();       
        } else { 
            // Get and keep the item id to use later
            $sql = "SELECT id FROM inventory WHERE BINARY name = ?;";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
              header('Content-Type: application/json');
              echo json_encode(['success' => false, 'message' => 'SQL error']);
              exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $itemName);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $resultId);
                mysqli_stmt_fetch($stmt);

                // If task_id is request
                $sql = "SELECT request_id FROM request;";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                  header('Content-Type: application/json');
                  echo json_encode(['success' => false, 'message' => 'SQL error']);
                  exit();
                } else {
                  mysqli_stmt_execute($stmt);
                  $result1 = mysqli_stmt_get_result($stmt);

                  while ($row = mysqli_fetch_assoc($result1)) {
                    // Check if the target value exists in any column of the current row
                    foreach ($row as $columnValue) {
                        if ($columnValue == $task_id) {
                            // Check if item exists in car inventory
                            $sql = "SELECT id FROM car_inv WHERE id = ?;";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                              header('Content-Type: application/json');
                              echo json_encode(['success' => false, 'message' => 'SQL error']);
                              exit();
                            } else {
                                mysqli_stmt_bind_param($stmt, "i", $resultId);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_store_result($stmt);

                                $resultCheck = mysqli_stmt_num_rows($stmt);

                                    if ($resultCheck == 0) {
                                      header('Content-Type: application/json');
                                      echo json_encode(['success' => false, 'message' => 'Item does not exist in car inventory']);
                                      exit();
                                    } else {
                                        // Check if item quantity is enough in the car inventory
                                        $sql = "SELECT quantity FROM car_inv WHERE id = ?";
                                        $stmt = mysqli_stmt_init($conn);

                                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                                          header('Content-Type: application/json');
                                          echo json_encode(['success' => false, 'message' => 'SQL error']);
                                          exit();
                                        } else {
                                            mysqli_stmt_bind_param($stmt, "i", $resultId);
                                            mysqli_stmt_execute($stmt);
                                            mysqli_stmt_bind_result($stmt, $database_quantity);
                                            mysqli_stmt_fetch($stmt);

                                            // Check if the requested quantity is greater than the database quantity
                                            if ($quantity > $database_quantity) {
                                              header('Content-Type: application/json');
                                              echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds the available quantity in the car inventory. Max quantity is ' . $database_quantity .'']);
                                              exit();
                                            } else {
                                                // Change quantity of item in car inventory
                                                $sql = "UPDATE car_inv SET quantity = quantity - ? WHERE id = ?;";
                                                $stmt = mysqli_stmt_init($conn);

                                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                  header('Content-Type: application/json');
                                                  echo json_encode(['success' => false, 'message' => 'SQL error']);
                                                  exit();
                                                } else {
                                                    mysqli_stmt_bind_param($stmt, "ii", $quantity, $resultId);
                                                    mysqli_stmt_execute($stmt);

                                                    // -1 curr_task
                                                    $sql = "UPDATE rescuer 
                                                    SET curr_task = curr_task - 1 WHERE resc_id = ?;";
                                                    $stmt = mysqli_stmt_init($conn);

                                                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                        $response = ['success' => false, 'message' => 'SQL error'];
                                                    } else {
                                                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                                                        mysqli_stmt_execute($stmt);
                                                    }

                                                    // Change task to complete
                                                    $sql = "UPDATE task SET active = false, complete = true, complete_date = NOW() WHERE task_id = ?;";
                                                    $stmt = mysqli_stmt_init($conn);

                                                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                      header('Content-Type: application/json');
                                                      echo json_encode(['success' => false, 'message' => 'SQL error']);
                                                      exit();
                                                    } else {
                                                        mysqli_stmt_bind_param($stmt, "i", $task_id);
                                                        mysqli_stmt_execute($stmt);

                                                        header('Content-Type: application/json');
                                                        echo json_encode(['success' => true, 'message' => 'Successful completion', 'redirect' => 'main_rescuer.php']);
                                                        exit();
                                                    }
                                                }
                                            }
                                        }
                                      }
                              }  
                            break;
                        }
                    }
                }
              }
            
                // If task_id is offer
                $sql = "SELECT donate_id FROM donation;";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                  header('Content-Type: application/json');
                  echo json_encode(['success' => false, 'message' => 'SQL error']);
                  exit();
                } else {
                  mysqli_stmt_execute($stmt);
                  $result1 = mysqli_stmt_get_result($stmt);
                  while ($row = mysqli_fetch_assoc($result1)) {
                    // Check if the target value exists in any column of the current row
                    foreach ($row as $columnValue) {
                        if ($columnValue == $task_id) {

                          // Load stuff in the car
                          $sql = "SELECT resc_id, id FROM car_inv 
                          WHERE resc_id = ? AND id = ?;";
                          $stmt = mysqli_stmt_init($conn);
                          if (!mysqli_stmt_prepare($stmt, $sql)) {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'message' => 'SQL error']);
                            exit();
                          } else {
                            mysqli_stmt_bind_param($stmt, "ii", $user_id, $resultId);
                            mysqli_stmt_execute($stmt);
                        
                            // Store the result set
                            mysqli_stmt_store_result($stmt);
                        
                            // Get the number of rows returned
                            $rowCount = mysqli_stmt_num_rows($stmt);
                        
                            if ($rowCount > 0) {
                              $sql = "UPDATE car_inv SET quantity = quantity + ? WHERE resc_id = ? AND id = ?;";
                              $stmt = mysqli_stmt_init($conn);
                              if (!mysqli_stmt_prepare($stmt, $sql)) {
                                header('Content-Type: application/json');
                                echo json_encode(['success' => false, 'message' => 'SQL error']);
                                exit();
                              } 
                              else {
                                mysqli_stmt_bind_param($stmt, "iii", $quantity, $user_id, $resultId);
                                mysqli_stmt_execute($stmt);
                              }
                            }
                            elseif($rowCount == 0){
                              $sql = "INSERT INTO car_inv (resc_id, id, quantity) VALUES (?, ?, ?);";
                              $stmt = mysqli_stmt_init($conn);
                              if (!mysqli_stmt_prepare($stmt, $sql)) {
                                header('Content-Type: application/json');
                                echo json_encode(['success' => false, 'message' => 'SQL error']);
                                exit();
                              } 
                              else {
                                mysqli_stmt_bind_param($stmt, "iii", $user_id, $resultId, $quantity);
                                mysqli_stmt_execute($stmt);
                              }


                            }
                            // -1 curr_task
                            $sql = "UPDATE rescuer 
                            SET curr_task = curr_task - 1 WHERE resc_id = ?;";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                $response = ['success' => false, 'message' => 'SQL error'];
                            } else {
                                mysqli_stmt_bind_param($stmt, "i", $user_id);
                                mysqli_stmt_execute($stmt);
                            }

                            // Change task to complete
                            $sql = "UPDATE task SET active = false, complete = true, complete_date = NOW() WHERE task_id = ?;";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                              header('Content-Type: application/json');
                              echo json_encode(['success' => false, 'message' => 'SQL error']);
                              exit();
                            } else {
                                mysqli_stmt_bind_param($stmt, "i", $task_id);
                                mysqli_stmt_execute($stmt);

                                header('Content-Type: application/json');
                                echo json_encode(['success' => true, 'message' => 'Successful completion', 'redirect' => 'main_rescuer.php']);
                                exit();
                            }
                        
                          }

                          break;
                        }
                      }
                }
              }
            }
              
          }
        }
      }
              
