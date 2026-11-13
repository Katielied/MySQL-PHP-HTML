<!DOCTYPE html>
<html>
<head>
  <title>ConnectSitter</title>
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
          
           border-radius: 5px;
           padding-top: 10px;
           padding-right: 10px;
           padding-bottom: 10px;
           padding-left: 10px;
       }
       label
       {
           font-size: 20px;
           text-align: center;
       }
  </style>
</head>
<body bgColor="#D3D3D3">
  <center><h1>Order Requests Assigned To You</h1></center>




<?php
  $conn = require __DIR__ . "/db_connect.php";
?>




<?php




// Validate user input.
$userID = isset($_POST["sitterID"]) ? $_POST["sitterID"] : '';
$sitterPass = isset($_POST["sitterPass"]) ? $_POST["sitterPass"] : '';




// Query to retrieve user details based on userID
$sqlVerifyUser = "SELECT userID, pass, role, firstName, lastName FROM people WHERE userID = '$userID';";
$resultVerifyUser = $conn->query($sqlVerifyUser);




if ($resultVerifyUser)
{
  // Check if there are rows in the result
  if ($resultVerifyUser->num_rows > 0)
  {
      $row = $resultVerifyUser->fetch_assoc();




      // Check if the entered password matches the current row's password
      if ($sitterPass == $row["pass"] && $row["role"] == 'S')
      {
          echo '<div style="text-align: center;font-size: 20px;">';
          echo "Correct authentication.";




          // Display welcome message
          echo "<p>Welcome " . $row["firstName"] . " " . $row["lastName"] . "</p>";
          echo '</div>';


      }     
      else
      {
          if($sitterPass != $row["pass"])
          {
              die("Invalid password.");
          }
          else
          {
              die("Incorrect login for Role Sitter");
          }
      }
  }
  else
  {
      die('<p style = "text-align: center; font-size: 20px;font-family: Arial, sans-serif;background-color: #f8d7da;">Username invalid.</p>');
  }
}
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
          <th>Client ID</th>
          <th>Date</th>
          <th>Status (Pending, Assigned, Completed)</th>
          <th>Confirmed by Sitter</th>
          <th>Confirmed by Client</th>
      </tr>




      <?php




          $sql = "UPDATE orders SET status = 'A' WHERE clientConfirmed = 1 AND sitterConfirmed = 1 AND status != 'C';";
          $result3 = $conn->query($sql);
        
          $sql = "UPDATE orders SET status = 'P' WHERE clientConfirmed = 0 AND sitterConfirmed = 1 AND status != 'C';";
          $result4 = $conn->query($sql);




          $sql = "UPDATE orders SET status = 'P' WHERE clientConfirmed = 1 AND sitterConfirmed = 0 AND status != 'C';";
          $result5 = $conn->query($sql);




          $sql = "UPDATE orders SET status = 'P' WHERE clientConfirmed = 0 AND sitterConfirmed = 0 AND status != 'C';";
          $result6 = $conn->query($sql);








          $sql = "SELECT orderNo, date, orderType, petType, clientID, status, clientConfirmed, sitterConfirmed FROM orders WHERE sitterID = '" . $_POST["sitterID"] . "';";
        
          $result = $conn->query($sql);




      if($result)
      {
          if ($result->num_rows > 0)
          {
              while ($row = $result->fetch_assoc())
              {
                  echo"<tr>
                          <td>" . $row["orderNo"] . "</td>
                          <td>" . $row["orderType"] . "</td>
                          <td>" . $row["petType"] . "</td>
                          <td>" . $row["clientID"] . "</td>
                          <td>" . $row["date"] . "</td>
                          <td>" . $row["status"] . "</td>
                          <td>" . $row["sitterConfirmed"] . "</td>
                          <td>" . $row["clientConfirmed"] . "</td>
                      </tr>";
              }
          }
          else
          {
              echo "<p style='text-align: center; font-size: 18pt;'>No orders available</p>";
          }
      }
  ?>




</table><br><br><br>




<center><h1>Your Comments</h1></center>




<table>
      <tr>
          <th>Order Number</th>
          <th>User ID</th>
          <th>Role</th>
          <th>Comments</th>
      </tr>




      <?php




          $sql = "SELECT o.orderNo as orderNo, c.userID as userID, p.role as role, c.comments as comments FROM orders o JOIN comments c ON o.orderNo = c.orderNo JOIN people p ON c.userID = p.userID WHERE o.sitterID = '" . $_POST["sitterID"] . "' AND c.userID != '" . $_POST["sitterID"] . "' AND p.role !=  'H';";
          $result = $conn->query($sql);
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
          else
          {
              echo "<p style='text-align: center; font-size: 18pt;'>No comments available</p>";
          }




          $conn->close();




      ?>
</table><br><br>




<form method="post" action="./sitterConfirm.php">
              <label for="sitterConfirmation">Confirm or Deny A request, Please enter 0 for deny, and 1 for confirm. :</label><br><br>
              <input type ="text" name = "orderNo2" placeholder="Enter the Order No.:"><br><br>
              <input type ="text" name = "sitterConfirmation" placeholder="Enter Confirmation:"><br><br>
              <input type = "submit" value = "SUBMIT" "accept"><br><br><br>
</form>




<form method="post" action="./sitterCompletion.php">
              <label for="sitterCompletion">Is an order completed? Type in the Order No, and your userName.</label><br><br>
              <input type ="text" name = "orderNo3" placeholder="Enter the Order No.:"><br><br>
              <input type ="text" name = "sitterComments" placeholder="Additional comments"><br><br>
              <input type ="text" name = "sitterID" placeholder="Your Username:"><br><br>
              <input type = "submit" value = "SUBMIT" "accept"><br><br><br><br>
      </form>    




      <center><a href="main.html"><button>MAIN PAGE</button></a></center>
 </body>
</html>








