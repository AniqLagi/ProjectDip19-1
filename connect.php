<?php
$servername = 'localhost:3306';
$username = 'root';
$password = ''; 
$dbname = 'projectdiploma2';

//create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//check connection
if($conn->connect_error) {
    die("connection failed: ". $conn->connect_error);
}
//echo"connected successfully";

?>