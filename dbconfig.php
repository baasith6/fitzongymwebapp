<?php

$servername="localhost:3307";
$username="root";
$password="";
$dbname="gymcenter";

$conn=mysqli_connect($servername,$username,$password,$dbname);

if(!$conn){
    die("connection failed: ".mysqli_connect_error());
}
