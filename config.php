<?php

$host = 'localhost';
$db = 'my_db';
$user = 'root';
$pass = 'Prince123#';

// create connection
$conn = new mysqli($host,$user,$pass,$db);

// check if the connection was successful

if ($conn->connect_error){
    die("Connection failed: ".conn->connect_error);
}

// echo "connected successfully";

?>