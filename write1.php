<?php
if(isset($_POST['title']))
{
$server="localhost";
$username="root";
$pass="";
$con=mysqli_connect($server,$username,$pass);
if(!$con)
{
    die("Couldn't connect to server: " . $con->error);
}

$title=$_POST['title'];
$content=$_POST['content'];
$description=$_POST['desc'];
$category=$_POST['category'];
}
session_start();
 $uid = $_SESSION["user_id"];
 
 if(isset($_POST['title']))
 {
$sql="INSERT INTO `blogger`.`content`(`userid`, `descr`, `content`, `title`, `category`)
 VALUES ('$uid','$description','$content','$title','$category')";

 if($con->query($sql))
 {
    echo "<script>alert('Inserted successfully in');</script>";
   
 }
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
    <style>
        p{
            display: inline;
            position: absolute;
            width: fit-content;
            left: 80%;
            top:15%;
            font-size: 35px;
            color: white !important;
                           
        }
        form{
            margin-left:20%;
            margin-top:9px;
            align-items: center;
            justify-content: center;
            
            width: 60%;
            padding: 10px;
            color: aliceblue;
            background-image: url("5.jpg");
            border-radius: 66px;
            background-size: cover;
            
        }
        select,input,label,textarea{
            font-size: 24px;
            padding: 6px;
            margin:10px 20px 10px 20px;
            width:90%;
            max-width: 850px;
        }
        #contentform{
            width: fit-content;
            margin:0 auto;
        }
        #content{
            max-width: 727px;
        }
    </style>
</head>
<body>
    <div class="main"><h1>Lekhak</h1></div>
    <nav class="navbar"><a href="home.php">Home</a>
    <a href="stories.php">Stories</a><a href="poems.php">Poems</a>
    <a href="write1.php">Write</a></nav>
    <?php echo "<p id='userpara'>$uid</p>" ?>
    <form action="write1.php" method="post" class="write">
        <div id="contentform">
        <label for="category">Category</label>
        <select id="category" name="category"><option>Poem</option>
            <option>Story</option>
            <option>Article</option></select><br>
            <input type="text" name="title" id="title" placeholder="Enter title">
            <input type="text" name="desc" id="desc" placeholder="Enter description about story/poem" required>
            <br><textarea name="content" id="content" placeholder="Begin writing your Story/Poem" required></textarea>
        <input type="submit" name="submit" id="submit"><input type="reset" value="Clear">
        </div>
        </form>
    <script>
        var myData = sessionStorage.getItem('userid');
        var names=document.getElementById("userpara");
      
    </script>
</body>
</html>