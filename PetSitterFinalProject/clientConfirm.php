<!DOCTYPE html>
<html>
<head>
  <!-- php file to allow the client to confirm a sitter or not. -->
  <title>clientConfirmed</title>
  <!--Adding css style.-->
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
  <center><h1>Client Confirmation</h1></center>


<?php
  $conn = require __DIR__ . "/db_connect.php";
?>
      <?php
          //REtrieving the value of the order.
          $orderNo = intval($_POST["orderNo"]);

          //Retrieving the sitters confirmation
          $clientConfirm = intval($_POST["clientConfirmation"]);

          //Finding the order number to see if it exists.
          $findOrder = "SELECT orderNo from orders WHERE orderNo = $orderNo;";
          $result = $conn->query($findOrder);

                            
          //Error in the query.
          if(!$result)
          {
              die("error.");
                                
          }
          //Else, the result ran.
          else if($result)
          {
              //If the result gives an output, meaning the order number exists.
              if($result->num_rows > 0)
              {
                  //If the client confirms,
                  if($clientConfirm == 1)
                  {
                      //Set their confirmed to 1.
                      $sql = "UPDATE orders SET clientConfirmed = 1 WHERE orderNo = " . $orderNo . ";";
                      $result = $conn->query($sql);
                      echo "<p style = 'text-align: center; font-size: 20px;'>You have accepted the order with the assigned sitter.</p>";
                  }
                  //If the client denies.
                  else if($clientConfirm == 0)
                  {
                      //setting their confirmation to 0.
                      $sql = "UPDATE orders SET sitterConfirmed = 0 WHERE orderNo = " . $orderNo . ";";
                      $result1 = $conn->query($sql);
                        
                      //Update the "sitterID" to null for the specific order
                      $sql = "UPDATE orders SET sitterID = null WHERE orderNo = " . $orderNo . ";";
                      $result2 = $conn->query($sql);
            
                      echo "<p style = 'text-align: center; font-size: 20px;'>Declined the Request, re assigned to another sitter.</p>";
                  }
                  //If invalid input from the user.
                  else if($clientConfirm != 1 && $clientConfirm != 0)
                  {
                      die('<p style = "text-align: center; font-size: 20px;">Invalid input, please enter 1 or 0 for confirmation.</p>');
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