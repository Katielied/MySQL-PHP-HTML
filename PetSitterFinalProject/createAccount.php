<?php




//Connecting to the database;
$conn = require __DIR__ . "/db_connect.php";




//Validating if any required fields are empty
if (empty($_POST["firstName"])) {
 die('<p style = "text-align: center; font-size: 20px;font-family: Arial, sans-serif;background-color: #f8d7da;">First name is required</p>');
}
if (empty($_POST["lastName"])) {
 die('<p style = "text-align: center; font-size: 20px;font-family: Arial, sans-serif;background-color: #f8d7da;">Last name is required</p>');
}




//Ensruing that no usernames can be the same.
if (empty($_POST["username"])) {
 die('<p style = "text-align: center; font-size: 20px;font-family: Arial, sans-serif;background-color: #f8d7da;">Username is required</p>');
}
$getSitter = "SELECT userID FROM people WHERE userID = '" . $_POST["username"] . "';";
$run2 = mysqli_query($conn, $getSitter);
if ($run2 && mysqli_num_rows($run2) > 0)
{
 die('<p style = "text-align: center; font-size: 20px; font-family: Arial, sans-serif;background-color: #f8d7da;">Invalid username, already taken.</p>');
}

//Validating if any required fields are empty
if (empty($_POST["password"])) {
 die('<p style = "text-align: center; font-size: 20px;background-color: #f8d7da;">Password is required</p>');
}
if (empty($_POST["role"])) {
 die('<p style = "text-align: center; font-size: 20px;background-color: #f8d7da;">Role is required</p>');
}
//Checking if the role is correct user input.
if ($_POST["role"] != 'C' && $_POST["role"] != 'H' && $_POST["role"] != 'S')
{
 die('<p style = "text-align: center; font-size: 20px;background-color: #f8d7da;">Please enter a valid role, either C, H, or S</p>');
}

$userID = $_POST['username'];
$pass = $_POST['password'];
$lastName = $_POST['lastName'];
$firstName = $_POST['firstName'];
$role = $_POST['role'];
$phoneNumber = $_POST['phoneNumber'];
$emailAddress = $_POST['emailAddress'];

//Inserting into the people table.
$query = "INSERT INTO people (userID, pass, lastName, firstName, role, phoneNumber, emailAddress, ipAddress) VALUES ('$userID', '$pass', '$lastName', '$firstName', '$role', '$phoneNumber', '$emailAddress', '10.74.44.17');";

$run = mysqli_query($conn, $query);

//If the user was submitted successfully, it will echo to the user that it was done.
if($run)
 {
    echo "<p style='text-align: center; font-size: 18pt;'>Account Was created successfully..</p>";
    //Selecting their account created.
    $query = "SELECT userID, pass, lastName, firstName, role, phoneNumber, emailAddress, ipAddress FROM people WHERE userID = '$userID';";
    $runAccount = mysqli_query($conn, $query);

    if($runAccount)
    {
       if($runAccount->num_rows == 1)
       {
          //Displaying their created account.
          while ($row = $runAccount->fetch_assoc())
          {
             echo "<p style = 'text-align:center; background-color: rgb(208, 220, 223); font-size: 20px;'>UserName: " . $row['userID'] . "</p<br>";
             echo "<br>Password: " . $row['pass'] . "<br>";
             echo "Last Name: " . $row['lastName'] . "<br>";
             echo "First Name: " . $row['firstName'] . "<br>";
             echo "Role: " . $row['role'] . "<br>";
             echo "Phone Number: " . $row['phoneNumber'] . "<br>";
             echo "Email: " . $row['emailAddress'] . "<br>";
             echo "Ip Address: " . $row['ipAddress'] . "<br>";
             echo "<hr>";
          }
       }

    }
 }
$conn->close();
?>
