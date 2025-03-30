<?php
include 'dbconnect.php';


$name=$_POST['name'];
$pass=$_POST['pass'];
$gender=$_POST['gender'];
$email=$_POST['email'];
$phone=$_POST['phone'];

$sql="INSERT INTO `regform`(`userid`, `password`, `gender`, `email`, `phone`) 
VALUES (?,?,?,?,?)";
$stmt=$con->prepare($sql);
$stmt->bind_param("sssss", $name, $pass, $gender, $email, $phone);
if($stmt->execute())
{

    echo "<script>alert('Account created successfully!'); window.location.href = 'index.html';</script>";
}
?>