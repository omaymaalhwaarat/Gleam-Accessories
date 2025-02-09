<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "accessory_web";


$conn = new mysqli($servername, $username, $password, $dbname);
if($conn-> connect_error){
    die("".$conn->connect_error);
}