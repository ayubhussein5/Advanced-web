<?php
// CHANGE THIS TO YOUR ACTUAL DATABASE CREDENTIALS
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "internet_programming"; //  your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Stop the script and display a connection error message
    die("Connection failed: " . $conn->connect_error);
}

// The connection variable $conn is now ready for use in other files.
?>