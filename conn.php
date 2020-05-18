<?php

// Configure Database Connection
$servername = "localhost";
$username = "root";
$password = "north5323";
$dbname = "northstar";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
