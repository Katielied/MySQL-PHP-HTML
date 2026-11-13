<!--Creating a php page for the faculty inputs to add into the grades database grades table.-->
<!DOCTYPE html>
<html>
<head>
    <!--Adding the styling from text/css-->
   <title>Connect</title>
    <style type="text/css">
		table, th, td {border: 1px solid black}
	</style>
</head>
<!--Making the background color the same as the other sites.-->
<body bgColor="#D3D3D3">
    <!--Adding a header for the grade submission, and centering it.-->
    <center><h1>Grade Submission</h1></center>
    <!--Creating a php, which will connect to the mysql database calle grades, using the servername, username and password-->
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "brutusloki";
        $dbname = "grades";

        //Creating a connection calling on the function.
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check for database connection errors
        if ($conn->connect_error){
            die("Connection Failed: " . $conn->connect_error . "<br>");
        }
        //If no errors:
        echo "<p style='text-align: center; font-size: 18pt;'>DB Connected successfully...</p>";
        echo"<br><br>";
    ?>
                <!--Creating another php which will crate a query of submitting the grades which the user inputted.-->
                <?php
                //Setting the variables to the user input using tthe POST method.
                    $studentID = $_POST["studentID"];
                    $courseID = $_POST['courseID'];
                    $term = $_POST['term'];
                    $grade = $_POST['grade'];

                    //Creating the query of inserting a row into the grades table in the database using the users input values.
                    $query = "INSERT INTO grades(courseID, studentID, term, grade) VALUES ('$courseID', '$studentID', '$term', '$grade');";

                    //Running the function using the database connection, and the above query statement.
                    $run = mysqli_query($conn, $query);

                    //If the grade was submitted successfully, it will echo to the user that it was done.
                    if($run){
                        echo "<p style='text-align: center; font-size: 18pt;'>Grade Was Submitted To the Database Successfully.</p>";
                    }

                //Closing the connection.
                $conn->close();
                ?>
      
</body>
</html>



