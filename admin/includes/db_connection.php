<?php
session_start(); // Start the session at the beginning

include 'db_config.php';

// Establish a connection
$conn = new mysqli($host, $user, $password);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the database name is set and valid
if (isset($_SESSION['db_name']) && !empty($_SESSION['db_name'])) {
    $db_name = $_SESSION['db_name'];
} else {
    // Set a default database name or handle the error
    die("No database selected. Please select a database first.");
}

// Select the database
if (!$conn->select_db($db_name)) {
    die("Failed to select database: " . $conn->error);
}
?>
