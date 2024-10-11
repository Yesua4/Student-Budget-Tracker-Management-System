<?php
$servername = "localhost"; // Change this if your MySQL server is remote
$username = "root";        // MySQL Workbench username
$password = "YourNewPassword";            // MySQL Workbench password
$dbname = "budget_tracker"; // The database you created in MySQL Workbench

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
