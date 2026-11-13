<!DOCTYPE html>
<html>
<head>
  <title>ConnectSitterConfirm</title>
  <!--Adding css styling.-->
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
  <center><h1>Sitter Confirmation</h1></center>

<?php
  $conn = require __DIR__ . "/db_connect.php";
?>

      <?php

              //REtrieving the value of the order.
              $orderNo2 = intval($_POST["orderNo2"]);

              //Retrieving the sitters confirmation
              $sitterConfirmation = intval($_POST["sitterConfirmation"]);
              //Finding the order number to see if it exists.
              $findOrder = "SELECT orderNo from orders WHERE orderNo = $orderNo2;";
              $result = $conn->query($findOrder);
      
              //Error in the query.
              if(!$result)
              {
                  die("error.");
                                
              }
              //If the query runs.
              else if($result)
              {
                  //If there are data in the tables which match.
                  if($result->num_rows > 0)
                  {
                      //If the sitter confirms.
                      if($sitterConfirmation == 1)
                      {
                          //Updating their confirmation into the system.
                          $sql = "UPDATE orders SET sitterConfirmed = 1 WHERE orderNo = " . $orderNo2 . ";";
                          $result2 = $conn->query($sql);
                         
                          echo "<p style='text-align: center; font-size: 20px;'>You have accepted the order.</p>";




                      }
                      //If the sitter declines.
                      if($sitterConfirmation == 0)
                      {
                          //Setting their confirmation
                          $sql = "UPDATE orders SET sitterConfirmed = 0 WHERE orderNo = " . $orderNo2 . ";";
                          $result3 = $conn->query($sql);
        
                          // Update the "sitterID" to null for the specific order
                          $sql = "UPDATE orders SET sitterID = null WHERE orderNo = " . $orderNo2 . ";";
                          $result4 = $conn->query($sql);

                          // $sql = "UPDATE orders SET sitterID = null WHERE orderNo = " . $orderNo2 . ";";
                          echo "<p style = 'text-align: center; font-size: 20px;'>Declined the Request, re-assigned to another sitter.</p>";
                      }
                      //If invalid input from the user.
                      else if($sitterConfirmation != 1 && $sitterConfirmation != 0)
                      {
                          die("Invalid input, please enter 1 or 0 for confirmation.");
                      }
                  }
                  //order number does not exist.
                  else
                  {
                      die('<p style = "text-align: center; font-size: 20px;">Invalid order number, please enter one that exists.</p>');
                  }
              }              
                 
          $conn->close();
      ?>


 </body>
</html>


