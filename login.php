<?php
session_start();
include 'dbconnect.php';
$name=$_POST['name'];
$pass=$_POST['pass'];
echo "<script type='text/javascript>
    sessionStorage.setItem('userid', '$name');</script>";

$sql="SELECT * FROM `regform` WHERE userid=? and password=? ";
$stmt=$con->prepare($sql);
$stmt->bind_param("ss", $name, $pass);
$stmt->execute();
$res=$stmt->get_result();
if($res->num_rows > 0){
    $_SESSION["user_id"] = $name;
    Header("Location: home.php");
}
else{
    echo "<script>alert('Invalid username or password'); window.location.href = 'index.html';</script>";
}

?>