<?php

$servername = "localhost";
$username = "root";
$password = "brutusloki";
$dbname = "petSitter";

      //Creating a connection calling on the function.
$conn = new mysqli($servername, $username, $password, $dbname);

      // Check for database connection errors
if ($conn->connect_error)
{
  die("Connection Failed: " . $conn->connect_error . "<br>");
}
      //If no errors:
echo "<p style='text-align: center; font-size: 15pt;'>DB Connected successfully...</p>";
echo"<br><br>";

return $conn;

?>
