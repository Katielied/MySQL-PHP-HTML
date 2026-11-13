<!DOCTYPE html>
<html>
<head>
  <title>ConnecthandlerAssign</title>
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
  <center><h1>Assign A Sitter</h1></center>

<?php
  $conn = require __DIR__ . "/db_connect.php";
?>


      <?php

              //Getting the order number
              $orderNo = intval($_POST["orderNo"]);
            
              //Checking so that the order does not update if the sitterID is already set to someone, with that oder number.
              $sqlOrder = "SELECT orderNo FROM orders WHERE sitterID is null AND orderNo = " . $orderNo . ";";
              $run2 = mysqli_query($conn, $sqlOrder);
              if ($run2 && mysqli_num_rows($run2) == 0)
              {
                  die('<p style = "text-align: center; font-size: 20px; font-family: Arial, sans-serif;background-color: #f8d7da;">Invalid Order Number</p>');
              }
              //Getting the sitterID
              $sitterID = $_POST["sitterUser"];
              //Validating sitterID
              if(empty($sitterID))
              {
                  die("Please enter the sitter's ID.");
              }
              $getSitter = "SELECT userID FROM people WHERE userID = '" . $sitterID . "';";
              $run2 = mysqli_query($conn, $getSitter);
              if ($run2 && mysqli_num_rows($run2) == 0)
              {
                  die('<p style = "text-align: center; font-size: 20px; font-family: Arial, sans-serif;background-color: #f8d7da;">invalid sitter ID</p>');
              }

              //Getting the handlers ID
              $handlerID = $_POST["handlerUser"];
              //Validating handlerID.
              if(empty($handlerID))
              {
                  die("Please enter the your ID.");
              }
              $getHandler = "SELECT userID FROM people WHERE userID = '" . $handlerID . "';";
              $run2 = mysqli_query($conn, $getHandler);
              if ($run2 && mysqli_num_rows($run2) == 0)
              {
                  die('<p style = "text-align: center; font-size: 20px;background-color: #f8d7da;">invalid handler ID</p>');
              }

              //Getting the comments.
              $comments = $_POST["comments"];

              //Setting the sitter to the order number.
              $query = "UPDATE orders SET sitterID = " . "'" . $sitterID . "'" . "WHERE orderNo = " . $orderNo . ";";
              $run = mysqli_query($conn, $query);

              //Checking if the wuery ran.
              if ($run)
              {
                  // Retrieve the orderNo from the orders table
                  $getOrder = "SELECT orderNo FROM orders WHERE orderNo = " . $orderNo . ";";
                  $run2 = mysqli_query($conn, $getOrder);

                  // Check if the query was successful
                  if ($run2 && mysqli_num_rows($run2) == 1)
                  {
                      //Get the value of the orderNo
                      $row = mysqli_fetch_assoc($run2);
                      $orderNo = $row['orderNo'];

                      //Insert comments
                      $query2 = "INSERT INTO comments(commentID, userID, orderNo, comments) VALUES (null, '$handlerID', '$orderNo', '$comments');";
                      $run3 = mysqli_query($conn, $query2);

                      //Check if comments were inserted successfully
                      if ($run3)
                      {
                          echo "<p style='text-align: center; font-size: 18px;'>Sitter was assigned successfully. Order No: $orderNo</p>";
                          if($comments != '')
                          {
                              echo "<p style = 'text-align: center; font-size: 18px;'>Comments were added successfully.</p>";
                          }
                          else
                          //If the comments was left blank, delete from the comments table.
                          {
                              $deleteComment = "DELETE FROM comments WHERE comments = '$comments'";
                              $run4 = mysqli_query($conn, $deleteComment);
                          }
                      }
                      //If the query did not run.
                      else
                      {
                          echo "<p style = 'text-align:center; font-size: 18px;'>Error adding comments.</p>";
                      }
                  }
                  //order number was not found.
                  else
                  {
                      echo "<p style = 'text-align: center; font-size: 18px;'>Error retrieving orderNo.</p>";
                      $query = "UPDATE orders SET sitterID = NULL WHERE orderNo = $orderNo";
                      $run = mysqli_query($conn, $query);
                  }
              }
              //If of error.
              else
              {
                  echo "<p style = 'text-align: center; font-size: 18px;'>There was an error in placing your request, please check to make sure your fields are correct.</p>";
              }
    
              $conn->close();
      ?>




 </body>
</html>