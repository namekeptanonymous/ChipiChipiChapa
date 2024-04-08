<?php
session_start();

$servername = "localhost";
$username = "24725301";
$password = "24725301";
$database = "db_24725301";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if userId exists in session
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 'NULL';

// Get the current page name
$pageName = basename($_SERVER['PHP_SELF']);

$sql = "INSERT INTO page_visits (page_name, timestamp, userId)
VALUES ('" . $pageName . "', NOW(), " . $userId . ")";

if ($conn->query($sql) !== TRUE) {
  echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
}

// Check if the user has already visited the website in this session
if (!isset($_SESSION['siteVisited'])) {
  $sql = "INSERT INTO total_visits (userId) VALUES ($userId)";
  // Execute the SQL query
  if ($conn->query($sql) !== TRUE) {
      echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
  }
  // Set the session variable to indicate that the site has been visited
  $_SESSION['siteVisited'] = true;
}



$conn->close();
?>