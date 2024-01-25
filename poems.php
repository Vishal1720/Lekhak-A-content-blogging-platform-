<?php 
session_start();
$uid = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
    <style>
        #poem{
            width: 50%;
            margin-left: 25%;
            margin-top: 5%;
            color: white;
            padding: 10px;
            padding-left: 20px;
            padding-right: 20px;
            background-image: url("4.jpg");
        }
        #userpara{
            display: inline;
            position: absolute;
            width: fit-content;
            left: 80%;
            top:15%;
            font-size: 35px;
            color: white !important;
                           
        }
        #content{
            font-size:23px;
            white-space: pre-line;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        h1{
            text-align: center;
        }
    </style>
</head>
<body>
<div class="main"><h1>Lekhak</h1></div>
    <nav class="navbar"><a href="home.php">Home</a>
    <a href="stories.php">Stories</a><a href="poems.php">Poems</a>
    <a href="write1.php">Write</a></nav><?php echo "<p id='userpara'>$uid</p>" ?><?php

$server="localhost";
$username="root";
$pass="";


$con=mysqli_connect($server,$username,$pass);
if(!$con)
{
   echo "Connection failed";
}
$sql="SELECT * FROM `blogger`.`content` WHERE category='poem'";

$result=$con->query($sql);

if($result->num_rows>0)
{
    foreach($result as $row){
        $head=$row['title'];
        $content=$row['content'];
        $writer=$row['userid'];
        echo"<div id='poem'><h1>$head</h1><h2>By $writer</h2>
        <p id='content'>$content</p></div>";
    }
}

    ?>
</body>
</html>