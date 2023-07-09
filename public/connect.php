<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "my_spbu";

// Create Connection
$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
}


?>