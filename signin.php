<?php
$server="localhost";
$username="root";
$password="";

$con=mysqli_connect($server,$username,$password);
if(!$con)
{
    die($con ->error);
}
echo "Connection established";

$name=$_POST['name'];
$pass=$_POST['pass'];
$gender=$_POST['gender'];
$email=$_POST['email'];
$phone=$_POST['phone'];

$sql="INSERT INTO `blogger`.`regform`(`userid`, `password`, `gender`, `email`, `phone`) VALUES ('$name','$pass','$gender','$email','$phone')";

if($con->query($sql))
{
    echo "Registered";
}
?>