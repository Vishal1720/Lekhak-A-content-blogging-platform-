<?php
session_start();
$server="localhost";
$username="root";
$password="";

$con=mysqli_connect($server,$username,$password);
if(!$con){
    die("Connection not established");
}
$name=$_POST['name'];
$pass=$_POST['pass'];
echo "<script type='text/javascript>
    sessionStorage.setItem('userid', '$name');</script>";
$sql="SELECT * FROM `blogger`.`regform` WHERE userid='$name' and password='$pass' ";
$res=$con->query($sql);
if($res->num_rows > 0){
    $_SESSION["user_id"] = $name;
    Header("Location: home.php");
}

?>