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
h2,p,h1{
    color:white;
}

p{
    white-space: pre-line;
    font-size:22px;
}
#storytitle{
font-size: 45px;
text-align: center;
margin-top:26px;
}

.story{
    border: 2px solid white;
    width:350px;
    height:250px;
    background-color: rgb(76, 95, 213);
    padding:16px;
    overflow: hidden;
    margin-left: 10px;
    margin-top: 10px;
    border-radius: 30px;
   
}
.storydiv{
   display: flex;
}
#auth{
    
    font-size: 22px;
    color:rgb(254, 251, 251);
    font-weight: bold;
    margin-bottom: 9px;
}
#userpara{
            display: inline;
            position: absolute;
            width: fit-content;
            left: 80%;
            top:15%;
            font-size: 35px;
            color: white !important;
            font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
                           
        }
        #storybg{
            background-image: url("23.jpg");
    background-size: cover;
    background-position: center;
        }
        #poembg{
            background-image: url("divimg.jpg");
    background-size: cover;
    background-position: center;
        }
        #highlight{
    background-color: white;
    color:black;
    border: 2px solid black;
}
    </style>
</head>
<body>
    <div class="main"><h1>Lekhak</h1></div>
    <nav class="navbar">
        <a href="home.php" id="highlight">Home</a>
        <a href="stories.php">Stories</a>
        <a href="poems.php">Poems</a>
        <a href="write1.php">Write</a><?php echo "<p id='userpara'>$uid</p>"?></nav>
    <h1>Stories</h1>
    
        <div class="storydiv">
        <?php 
    $servername="localhost";
    $username="root";
    $pass="";

    $con=mysqli_connect($servername,$username,$pass);
    
    $sql="SELECT * FROM `blogger`.`content` WHERE category='story'";

    $res=$con->query($sql);

    if($res->num_rows>0){
        foreach($res as $row){
            $storytitle=$row['title'];
            $storydesc=$row['descr'];
            $uid=$row['userid'];
            echo "
            <div class='story' id='storybg'>
            <h2 id='storytitle'>$storytitle</h2><span id='auth'>By $uid</span>
            <p id='storypara'><span><strong>Description:</strong></span>
            $storydesc </p>
            </div>";
        }
    }

    ?></div>
    <h1>Poems</h1>
    <div class="storydiv">
    <?php 
    $sql="SELECT * FROM `blogger`.`content` WHERE category='poem'";
    $result=$con->query($sql);
foreach($result as $rows)
{
    $title=$rows['title'];
    $description=$rows['descr'];
    $uid=$rows['userid'];
    echo "
            <div class='story' id='poembg'>
            <h2 id='storytitle'>$title</h2><span id='auth'>By $uid</span>
            <p id='storypara'><span><strong>Description:</strong></span>
            $description </p>
            </div>";
}
    ?>
    </div>
</body>
</html>