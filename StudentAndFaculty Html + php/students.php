<!--Php file used to connect the studentID to the mySql Database.-->
<!DOCTYPE html>
<html>
<head>
    <!--Creating styling for the site-->
    <!--Adding the styling for the table-->
    <title>ConnectStudent</title>
        <style type="text/css">
		    table, th, td {border: 1px solid black}
        table{
            margin: auto;
            text-align: center;
            width: 80%;
            font-size: 16px;
        }
        
	    </style>
</head>
<!--Adding a background color for the site which matches the rest of the sites.-->
<body bgColor="#D3D3D3">
    <!--Adding a header for the student transcript, centering it and setting its size.-->
    <center><h1>Student Transcript</h1></center>
    <!--Creating a php body, which will connect to the mysql database, else it will notify the user-->
    <?php
        //Connecting using the servername, username, mysql passwork and database name.
        $servername = "localhost";
        $username = "root";
        $password = "brutusloki";
        $dbname = "grades";

        //Creating a connextion using the function new mysqli
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check for database connection errors
        if ($conn->connect_error){
            die("Connection Failed: " . $conn->connect_error . "<br>");
        }
        //Output to user if connected successfully.
        echo "<p style='text-align: center; font-size: 18pt;'>DB Connected successfully...</p>";
        "<br>";
	
    ?>
    <!--Creating the table headers for the transcript.-->
    <table>
            <tr>
                <th>Student ID</th>
                <th>Course ID</th>
                <th>Term</th>
                <th>Grade</th>
            </tr>

            <!--Creating another php in order to write the query to recieve the students grades.-->
            <?php

                //IF the student does not enter an id, leaves it blank.
                if($_POST["studentID"] == "")
                {
                    echo "<p style='text-align: center; font-size: 18pt;'>No Grades Available, Please Enter an ID
                    </p>";
                }

                //If the id is entered, it will select their grades using the sql statement.
                else
                {
                    $sql = "SELECT studentID, courseID, term, grade FROM grades WHERE studentID = " . $_POST["studentID"] .";";

                    //setting the result to the connection of the database using the query written above.
                    $result = $conn->query($sql);

                    //As long as there are more values in the result,
                    if ($result->num_rows > 0)
                   {
                        //It will get an associative array of the results.
                        //Echo them out to the user in a table, row by row.
                       while($row = $result->fetch_assoc())
                       {
                           echo "<tr>
                                       <td>" . $row["studentID"] . "</td>
                                       <td>" . $row["courseID"] . "</td>
                                       <td>" . $row["term"] . "</td>
                                       <td>" . $row["grade"] . "</td>
                                </tr>";
                  
                       }
                   }
                   //If The student does not have any grades available.
                   else
                   {
                       echo "<p style='text-align: center; font-size: 18pt;'>No Grades Available</p>";
                   }

                //Closing the connection.
                }
                $conn->close(); 
            ?>
    </table>

</body>
</html>

