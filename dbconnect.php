<?php 

$server="localhost";
$username="root";
$password="";
$db='blogger';
$con=mysqli_connect($server,$username,$password,$db);
if(!$con)
{
    die($con ->error);
}