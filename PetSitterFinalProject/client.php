<!DOCTYPE html>
<html>
<head>
  <!-- PHP page for client home page -->
  <title>ConnectClient</title>
  <!-- adding css style -->
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
      form
      {
           text-align: center;
           font-size: 15px;
          
           border-radius: 5px;
           padding-top: 10px;
           padding-right: 10px;
           padding-bottom: 10px;
           padding-left: 10px;
      }
      input
       {
           font-size: 15px;
          
           border-radius: 3px;
           padding-top: 3px;
           padding-right: 3px;
           padding-bottom: 3px;
           padding-left: 3px;
       }
       button
       {
           text-align: center;
           border-radius: 5px;
           padding-top: 10px;
           padding-right: 10px;
           padding-bottom: 10px;
           padding-left: 10px;
       }
       label
       {
           text-align: center;
           font-size: 20px;
          
       }
  </style>
</head>
<body bgColor="#D3D3D3">
  <center><h1>Your Orders</h1></center>


<?php
//Connecting to the database.
  $conn = require __DIR__ . "/db_connect.php";
?>


  <?php


      //validating user input.
      $userID = isset($_POST["userID"]) ? $_POST["userID"] : '';
      $clientPass = isset($_POST["clientPass"]) ? $_POST["clientPass"] : '';


      //getting user inputs details.
      $sqlVerifyUser = "SELECT userID, pass, role, firstName, lastName FROM people WHERE userID = '$userID';";
      $resultVerifyUser = $conn->query($sqlVerifyUser);


      //If query ran,
      if ($resultVerifyUser)
      {
          //check if there are usernames with the corresponding userID
          if ($resultVerifyUser->num_rows > 0)
          {
              //Fetch the rows.
              $row = $resultVerifyUser->fetch_assoc();


              // Check if the entered password matches the current row's password, and if the role is correct.
              if ($clientPass == $row["pass"] && $row["role"] == 'C')
              {
                  echo '<div style="text-align: center;font-size: 20px;">';
                  echo "Correct authentication.";

                  //If it is correct log in, display echo.
                  echo "<p>Welcome " . $row["firstName"] . " " . $row["lastName"] . "</p>";
                  echo '</div>';

              }  
              //Else 
              else
              {
                  //If the password does not match
                  if($clientPass != $row["pass"])
                  {
                      die("Invalid password.");
                  }
                  //If the user logged into the wrong site for the wrong role.
                  else
                  {
                      die("Incorrect login for Role Client");
                  }
              }
          }
          //Else, username is invalid;
          else
          {
              die("<p style = 'text-align: center; font-size: 20px;'>Username invalid.</p>");
          }
      }
      //Problem when trying to run the query.
      else
      {
          die("Error executing the query: " . $conn->error);
      }


  ?>


<table>
      <tr>
          <th>Order Number</th>
          <th>Order Type (Grooming, Walking Petting)</th>
          <th>Pet Type (Dog, Cat, or Both)</th>
          <th>Sitter ID</th>
          <th>Date</th>
          <th>Status (Pending, Assigned, Completed)</th>
          <th>Confirmed by Sitter</th>
          <th>Confirmed by Client</th>
      </tr>

   <?php

      //Making sure to update the status of the request from user, every time they refresh the page
      //Making sure to get the most updated version of status in case of additional confirmations.
      $sql = "UPDATE orders SET status = 'A' WHERE clientConfirmed = 1 AND sitterConfirmed = 1 AND status != 'C';";
      $result3 = $conn->query($sql);

      $sql = "UPDATE orders SET status = 'P' WHERE clientConfirmed = 0 AND sitterConfirmed = 1 AND status != 'C';";
      $result4 = $conn->query($sql);

      $sql = "UPDATE orders SET status = 'P' WHERE clientConfirmed = 1 AND sitterConfirmed = 0 AND status != 'C';";
      $result5 = $conn->query($sql);

      $sql = "UPDATE orders SET status = 'P' WHERE clientConfirmed = 0 AND sitterConfirmed = 0 AND status != 'C';";
      $result6 = $conn->query($sql);

      // Displaying users orders in a table;
      $sql = "SELECT DISTINCT orderNo, date, orderType, petType, sitterID, status, clientConfirmed, sitterConfirmed FROM orders WHERE orders.clientID = '" . $_POST["userID"] . "';";
      $result = $conn->query($sql);


      //If they have orders we will display them.
      if ($result->num_rows >= 0)
      {
          while ($row = $result->fetch_assoc())
          {
              echo"<tr>
                      <td>" . $row["orderNo"] . "</td>
                      <td>" . $row["orderType"] . "</td>
                      <td>" . $row["petType"] . "</td>
                      <td>" . $row["sitterID"] . "</td>
                      <td>" . $row["date"] . "</td>
                      <td>" . $row["status"] . "</td>
                      <td>" . $row["sitterConfirmed"] . "</td>
                      <td>" . $row["clientConfirmed"] . "</td>
                  </tr>";
          }
      }
      //Else they have no orders;
      else
      {
          echo "<p style='text-align: center; font-size: 18px;'>No orders available</p>";
      }
  ?>


</table><br><br>


<center><h1>Your Comments</h1></center>


<table>
      <tr>
          <th>Order Number</th>
          <th>User ID</th>
          <th>Role</th>
          <th>Comments</th>
      </tr>

      <?php

          //Creating a table of comments where the user can see all the comments made on their orders, and sitter recconmmendations.
          $sql = "SELECT comments.userID AS userID, people.role AS role, comments.orderNo AS orderNo, comments.comments AS comments FROM comments JOIN people ON comments.userID = people.userID JOIN orders ON comments.orderNo = orders.orderNo WHERE orders.clientID = '" . $_POST["userID"] . "';";
          $result = $conn->query($sql);

          //Displaying all of the rows that match this query;
          if ($result->num_rows > 0)
          {
              while ($row = $result->fetch_assoc())
              {
                  echo"<tr>
                          <td>" . $row["orderNo"] . "</td>
                          <td>" . $row["userID"] . "</td>
                          <td>" . $row["role"] . "</td>
                          <td>" . $row["comments"] . "</td>
                      </tr>";
              }
          }
          //They have no comments, else.
          else
          {
              echo "<p style='text-align: center; font-size: 18px;'>No comments available</p>";
          }




          $conn->close();
      ?>


<!-- promprting if they would like to make a new order. -->
<center><a href="createOrder.html"><button>MAKE AN ORDER</button></a></center><br><br>

<!-- inputs for if they would like to confirm or deny an updated sitter. -->
<form method="post" action="./clientConfirm.php">
              <center><label for="cid">Confirm or Deny Your Sitter, Please enter 0 for deny, and 1 for confirm. :</label></center><br><br>
              <center><input type ="text" name = "orderNo" placeholder="Enter the Order No:"></center><br>
              <center><input type ="text" name = "clientConfirmation" placeholder="Enter Confirmation":></center><br>
              <center><input type = "submit" value = "SUBMIT" "accept"></center><br><br>
      </form>
<br><br>

<!-- inputs for if they would like to confirm or deny completion by sitter. -->
<form method='post' action='./clientCompletion.php'>
                          <center><label for='cid'>Has A Request been marked completed by the Sitter? If so, please type y to confirm this or n to deny confirmation:</label></center><br><br>
                          <center><input type='text' name='clientCompletion' placeholder='Enter Completion:'></center><br>
                          <center><input type ="text" name = "orderNo" placeholder="Enter the Order No:"></center><br>
                          <center><input type='submit' value='SUBMIT' 'accept'></center><br><br><br><br>
                      </form>




                      <!-- reference back to home page. -->
                      <center><a href="main.html"><button>MAIN PAGE</button></a></center><br><br>

 </body>
</html>