<!DOCTYPE html>
<html>
<head>
  <!-- php file to allow the client to confirm completion or not. -->
  <title>ConnectClientCompletion.</title>
  <!--adding css style.-->
  <style type="text/css">
      table, th, td {
          border: 1px solid black;
      }
      table {
          margin: auto;
          text-align: center;
          width: 80%;
          font-size: 16px;
      }
  </style>
</head>
<body bgColor="#D3D3D3">
  <center><h1>Client Confirmation Of Completion</h1></center>

  <?php
      $conn = require __DIR__ . "/db_connect.php";
  ?>

  <?php
      // Retrieve the value of the order.
      $orderNo = intval($_POST["orderNo"]);

      // Retrieving the sitter's confirmation.
      $clientComplete = $_POST["clientCompletion"];

      // Finding the order number.
      $findOrder = "SELECT orderNo FROM orders WHERE orderNo = $orderNo;";
      $result = $conn->query($findOrder);

      // If the result does not work,
      if(!$result)
      {
          die("Error.");
      }
      // Else the result runs.
      else if($result)
      {
          // If its rows are larger than 0, meaning that the orderNo exists
          if($result->num_rows > 0)
          {
              // Check if the client has entered yes
              if($clientComplete === 'y')
              {
                  // If 'y', select to make sure that the orderNo has been marked as completed by the sitter.
                  $sql = "SELECT status, orderNo FROM orders WHERE status = 'C' AND orderNo = $orderNo;";
                  $result2 = $conn->query($sql);

                  // If a row exists, the order has been marked as completed by the sitter.
                  if ($result2->num_rows > 0)
                  {
                      //deleting the foreign key of comments
                      $sqlDeleteComments = "DELETE FROM comments WHERE orderNo = $orderNo;";
                      $resultComments = mysqli_query($conn, $sqlDeleteComments);
                    
                      //If it was successful, and there was a comment
                      if($resultComments)
                      {
                          //Delete the order.
                          $sqlDeleteOrders = "DELETE FROM orders WHERE orderNo = $orderNo;";
                          $resultOrders = mysqli_query($conn, $sqlDeleteOrders);
                        
                          // If successful, the order is complete and deleted.
                          if($resultOrders)
                          {
                              echo "'Order has been completed.";
                          } else
                          {
                              // Handle the case where the order deletion fails.
                              echo "'Error deleting the order: " . mysqli_error($conn);
                          }
                      }
                      //If there was no comment connecting to this specific order number,
                      else
                      {
                          // Ignore errors when trying to delete something that doesn't exist and continue.
                          $sqlDeleteOrders = "DELETE FROM orders WHERE orderNo = $orderNo;";
                          $resultOrders = mysqli_query($conn, $sqlDeleteOrders);




                          //If the order was deleted successfully.
                          if($resultOrders)
                          {
                              echo "<p style = 'text-align: center; font-size: 20px;>'Order has been completed.</p>";
                          }
                          else
                          {
                              //Else, there was an error.
                              echo "Error deleting the order: " . mysqli_error($conn);
                          }
                      }
                  }
                  // The order has not been marked as completed by the sitter.
                  else
                  {
                      die("<p style = 'text-align: center; font-size: 20px;'>This order has not been completed yet.</p>");
                  }
              }
              //If the user types N, meaning the order was not completed yet.
              else if($clientComplete == 'n')
              {
                  //Setting the status back to assigned.
                  $sql2 = "UPDATE orders SET status = 'A' WHERE orderNo = $orderNo;";
                  $result3 = $conn->query($sql2);

                  //If the result ran, the completion was denied by the client.
                  if($result3)
                  {
                      echo "Completion has been denied.";
                  }
                  //Else, the result did not fun.
                  else
                  {
                      echo "Error updating order status: " . mysqli_error($conn);
                  }
              }
              //The user did not type in y or n.
              else
              {
                  die("Please enter 'y' or 'n'.");
              }
          }
          //The user did not type in a valid order number which exists.
          else
          {
              die('<p style = "text-align: center; font-size: 20px;">Please enter a valid order number.</p>');
          }
      }

      $conn->close();
  ?>
</body>
</html>