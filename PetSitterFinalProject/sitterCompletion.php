<!DOCTYPE html>
<html>
<head>
    <title>ConnectSitterCompletion</title>
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
    <center><h1>Sitter Completion</h1></center>

<?php 
    $conn = require __DIR__ . "/db_connect.php";
?>


        <?php

                //Retrieving the sitters comments: 
                $sitterComments = $_POST["sitterComments"];

                //Retrieving the sitters id.
                $sitterID = $_POST["sitterID"];
                //Validating sitterID
                if(empty($sitterID))
                {
                    die("Please enter the sitter's ID.");
                }
                $getSitter = "SELECT userID FROM people WHERE userID = '" . $sitterID . "' AND role = 'S';";
                $run2 = mysqli_query($conn, $getSitter);
                if ($run2 && mysqli_num_rows($run2) == 0) 
                {
                    die("invalid sitter ID");
                }

                //REtrieving the value of the order.
                $orderNo = intval($_POST["orderNo3"]);
                $findOrder = "SELECT orderNo from orders WHERE orderNo = $orderNo;";
                $result = $conn->query($findOrder);

                //If there is a result
                if ($result && mysqli_num_rows($result) == 1) 
                {
                    //Set the status to completed
                    $sql = "UPDATE orders SET status = 'C' WHERE orderNo = " . $orderNo . ";";
                    $result2 = $conn->query($sql);
                
                    //If query runs,
                    if($result2)
                    {
                        //Order is completed
                        echo "Completed Order";
                        //Inserting comments
                        $query2 = "INSERT INTO comments(commentID, userID, orderNo, comments) VALUES (null, '$sitterID', '$orderNo', '$sitterComments');";
                        $run3 = mysqli_query($conn, $query2);

                        //Check if comments were inserted successfully
                        if ($run3) 
                        {
                            if($sitterComments != '')
                            {
                                echo "<p>Comments were added successfully.</p>";
                            }
                            //If they did not enter any comments:
                            else
                            {
                                //Delete the comment.
                                $deleteComment = "DELETE FROM comments WHERE comments = '$sitterComments'";
                                $run4 = mysqli_query($conn, $deleteComment);
                            }
                        } 
                        //Error in the query.
                        else 
                        {
                            echo "<p>Error adding comments.</p>";
                        }
                    }
                }
                //If order number is invalid.
                else{
                    die("Invalid orderNo, that request does not exist .");
                }


            $conn->close();
        ?>

     


    
</body>
</html>

