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
    p{
        font-size:20px;
        margin:10px;
            white-space: pre-line;
            font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }
    .story{
       
            width: 70%;
            height:600px;
            overflow: hidden;
            margin-left: 15%;
            margin-top: 5%;
            color: white;
            padding: 10px;
            padding-left: 20px;
            padding-right: 20px;
            background-image: url("bg.jpg");
        background-size: contain;
    }
    #title{
        font-size: 44px;
        text-align: center;
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
</style>
</head>
<body>
    <div class="main"><h1>Lekhak</h1></div>
    <nav class="navbar"><a href="home.html">Home</a><a href="stories.php">Stories</a><a href="poems.php">Poems</a><a href="write1.php">Write</a><?php echo "<p id='userpara'>$uid</p>"?></nav>
    <?php 
    $username="root";
    $server="localhost";
    $pass="";

    $con=mysqli_connect($server,$username,$pass);
    if(!$con)
    {
        die("Couldn't connect to server");
    }
    else{
        
        $sql="SELECT * FROM `blogger`.`content` WHERE category='story'";
        $res=$con->query($sql);
        if($res->num_rows>0)
        {
          foreach($res as $row)
          {
            $heading=$row['title'];
            $content=$row['content'];
            echo "   <div class='story'>
            <h1 id='title'>$heading</h1>
            <h2>By $uid</h2>
            <p>$content</p>
        </div>";
          }  
        }
    }
 
    
    ?>
    <script>
        let bool =[];
        var content =document.getElementsByClassName("story");
        for(let i=0;i<content.length;i++)
        {
            bool[i]=true;
        }
for(let i=0;i<content.length;i++)
{
    content[i].addEventListener("click",function(){
        
if(bool[i]){
    content[i].style.transition="13s";
    content[i].style.height="fit-content";
    bool[i]=false;
}
else{
    content[i].style.transition="13s";
    content[i].style.height="600px";
    bool[i]=true;
}
    });
}
    </script>
</body>
</html>