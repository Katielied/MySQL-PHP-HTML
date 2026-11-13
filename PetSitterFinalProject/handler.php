<!DOCTYPE html>
<html>
<head>
  <title>ConnectHandler</title>
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
  <center><h1>Current Orders Without A Sitter</h1></center>




<?php
  $conn = require __DIR__ . "/db_connect.php";
?>




<?php




//validating the users input.
$userID = isset($_POST["handlerID"]) ? $_POST["handlerID"] : '';
$handlerPass = isset($_POST["handlerPass"]) ? $_POST["handlerPass"] : '';




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
      if ($handlerPass == $row["pass"] && $row["role"] == 'H')
      {
          echo '<div style="text-align: center;font-size: 20px;">';
          echo "Correct authentication.";




          // Display welcome message
          echo "<p>Welcome " . $row["firstName"] . " " . $row["lastName"] . "</p>";
          echo'</div>';




      }     
      else
      {
          if($handlerPass != $row["pass"])
          {
              die("Invalid password.");
          }
          else
          {
              die("Incorrect login for Role Handler");
          }
      }
  }
  else
  {
      die("Username invalid.");
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
          <th>Date Ordered</th>
          <th>Order Type (Grooming, Walking Petting)</th>
          <th>Pet Type (Dog, Cat, or Both)</th>
          <th>Comments</th>
          <th>Client Last Name</th>
          <th>Client First Name</th>
          <th>Client UserName</th>
      </tr>




      <?php
          $sql = "SELECT o.orderNo, o.date, o.orderType, o.petType, c.comments, p.lastName as lastName, p.firstName as firstName, clientID FROM orders o JOIN people p ON p.userID = o.clientID LEFT JOIN comments c on c.orderNo = o.orderNo WHERE o.sitterID is NULL;";




          //$sql = "SELECT orderNo, date, orderType, petType, comments, people.lastName AS lastName, people.firstName AS firstName FROM orders JOIN people ON people.userID = orders.clientID WHERE sitterID IS NULL;";
          $result = $conn->query($sql);




          //if ($result)
          //{
          if ($result->num_rows > 0)
          {
              while ($row = $result->fetch_assoc())
              {
                  echo"<tr>
                          <td>" . $row["orderNo"] . "</td>
                          <td>" . $row["date"] . "</td>
                          <td>" . $row["orderType"] . "</td>
                          <td>" . $row["petType"] . "</td>
                          <td>" . $row["comments"] . "</td>
                          <td>" . $row["lastName"] . "</td>
                          <td>" . $row["firstName"] . "</td>
                          <td>" . $row["clientID"] . "</td>
                      </tr>";
              }
          }
          else
          {
              echo "<p style='text-align: center; font-size: 18pt;'>No orders available</p>";
          }




  ?>




</table><br><br>




<center><h1>Client Order Comments</h1></center>




<table>
      <tr>
          <th>Order Number</th>
          <th>User ID</th>
          <th>Role</th>
          <th>Comments</th>
      </tr>




      <?php




          //Creating a table for comments where the handler can only see the comments of the order made by the client.
          $sql = "SELECT c.orderNo, c.userID, p.role, c.comments FROM comments c JOIN orders o ON c.orderNo = o.orderNo JOIN people p ON c.userID = p.userID WHERE p.role = 'C' AND o.sitterID is NULL;";
          //$sql = "SELECT comments.userID AS userID, people.role AS role, comments.orderNo AS orderNo, comments.comments AS comments FROM comments JOIN people ON comments.userID = people.userID JOIN orders ON comments.orderNo = orders.orderNo WHERE orders.clientID = '" . $_POST["userID"] . "';";
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




<form method="post" action="./AssignASitter.php">
              <center><label for="cid">Assign a Sitter, Please enter your userID ONLY if you have added comments. :</label><br><br></center>
             <center><input type ="text" name = "orderNo" placeholder="Enter the Order No.:"><br></center><br>
             <center><input type ="text" name = "sitterUser" placeholder="Enter Sitter Username":><br></center><br>
             <center><input type ="text" name = "comments" placeholder="Enter Comments":><br></center><br>
             <center><input type ="text" name = "handlerUser" placeholder="Enter Your Username":><br></center><br>
             <center><input type = "submit" value = "SUBMIT" "accept"></center><br><br>
      </form>




      <center><a href="main.html"><button>MAIN PAGE</button></a></center><br><br><br>
</body>
</html>