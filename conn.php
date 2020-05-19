<?php

// Configure Database Connection
$servername = "localhost";
$username = "USERHERE";
$password = "PASSWORDHERE";
$dbname = "DBNAMEHERE";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
