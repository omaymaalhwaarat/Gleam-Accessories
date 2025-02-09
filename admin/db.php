<?php



$host = 'localhost';
$username = 'root';
$password = ''; // Replace with your database password
$database = 'accessory_web'; // Replace with your database name

// Create a MySQLi connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Optionally, you can set the charset to utf8mb4 for better compatibility
$mysqli->set_charset("utf8mb4");


// You can now use $mysqli to interact with the database
?>