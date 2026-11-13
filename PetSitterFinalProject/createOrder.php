<?php
//Connect to database:
$conn = require __DIR__ . "/db_connect.php";




//Validating all userInputs
if (empty($_POST["clientID"])) {
 die("Username Confirmation is required.");
}
//Checking to see if username exists.
$getSitter = "SELECT userID FROM people WHERE userID = '" . $_POST["clientID"] . "';";
$run2 = mysqli_query($conn, $getSitter);
if ($run2 && mysqli_num_rows($run2) == 0)
{
 die("Invalid username, does not exist.");
}
else
{
 //Checking to see if they are a client.
 $getRole = "SELECT userID FROM people WHERE userID = '" . $_POST["clientID"] . "' AND role = 'C';";
 $run3 = mysqli_query($conn, $getRole);
 if ($run3 && mysqli_num_rows($run3) == 0)
 {
    die("Invalid username for role CLIENT.");
 }
}

if (empty($_POST["orderType"])) 
{
 die("Order Type is required.");
}
//Checking to see if they have entered the correct order type.
if ($_POST["orderType"] != 'W' && $_POST["orderType"] != 'P' && $_POST["orderType"] != 'G')
{
 die("Please enter a valid order type, either W, P, or G");
}
if (empty($_POST["petType"])) 
{
 die("pet Type is required.");
}
//Checking if they have entered the correct pet type.
if ($_POST["petType"] != 'C' && $_POST["petType"] != 'D' && $_POST["petType"] != 'CD')
{
 die("Please enter a valid pet Type, either C, D, or CD");
}

$orderType = $_POST['orderType'];
$clientID = $_POST['clientID'];
$petType = $_POST['petType'];
$comments = $_POST['comments'];
$date = $_POST['date'];

//Creating an insert statement to add the order into orders.
$query = "INSERT INTO orders(orderNo, orderType, petType, clientID, sitterID, status, clientConfirmed, sitterConfirmed, date) VALUES (null, '$orderType', '$petType', '$clientID', null, 'P', 0, 0, '$date')";
$run = mysqli_query($conn, $query);


// Check if the order was inserted successfully
if ($run) 
{
  // Retrieve the orderNo from the orders table
  $getOrder = "SELECT orderNo FROM orders WHERE orderType = '$orderType' AND petType = '$petType' AND clientID = '$clientID' AND date = '$date'";
  $run2 = mysqli_query($conn, $getOrder);

  // Check if the SELECT query was successful
  if ($run2 && mysqli_num_rows($run2) == 1)
  {
      $row = mysqli_fetch_assoc($run2);
      $orderNo = $row['orderNo'];

      // Insert comments into the comments table
      $query2 = "INSERT INTO comments(commentID, userID, orderNo, comments) VALUES (null, '$clientID', '$orderNo', '$comments');";
      $run3 = mysqli_query($conn, $query2);

      // Check if comments were inserted successfully
      if ($run3)
      {
       //If they entered something for comments:
          echo "<p style='text-align: center; font-size: 18pt;'>Order was placed successfully. Order No: $orderNo</p>";
          if($comments != '')
          {
             echo "<p style = 'text-align: center; font-size: 18pt;'>Comments were added successfully.</p>";
          }
          //If they had no comments
          else
          {
             //Delete the comment.
             $deleteComment = "DELETE FROM comments WHERE comments = '$comments'";
             $run4 = mysqli_query($conn, $deleteComment);
          }
      }
      //If the query had an error trying to add a comment.
      else
      {
          echo "<p style = 'text-align: center; font-size: 18px;'>Error adding comments.</p>";
      }
  }
  //If the order number could not be retrieved.
  else
  {
      echo "<p style = 'text-align: center; font-size: 18px;'>Error retrieving orderNo.</p>";
  }
}
//If any type of user input fails.
else
{
  echo "<p style = 'text-align: center; font-size: 18px;'>There was an error in placing your request, please check to make sure your fields are correct.</p>";
}
            

$conn->close();
?>

<center><a href="main.html"><button>BACK</button></a></center>