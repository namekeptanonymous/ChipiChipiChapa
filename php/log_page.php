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

if ($userId === 'NULL') {
  $stmt = $conn->prepare("INSERT INTO page_visits (page_name, timestamp, userId) VALUES (?, NOW(), NULL)");
  $stmt->bind_param("s", $pageName);
} else {
  $stmt = $conn->prepare("INSERT INTO page_visits (page_name, timestamp, userId) VALUES (?, NOW(), ?)");
  $stmt->bind_param("si", $pageName, $userId);
}
$stmt->execute();

if($stmt->errno) {
  echo "<script>alert('Error: " . $sql . " " . $conn->error . "');</script>";
  exit("Error: " . $sql . "<br>" . $conn->error);
}

$result = $stmt->get_result();

// Check if the user has already visited the website in this session
if (!isset($_SESSION['siteVisited'])) {
  if ($userId === 'NULL') {
    $stmt = $conn->prepare("INSERT INTO total_visits (userId) VALUES (NULL)");
  } else {
    $stmt = $conn->prepare("INSERT INTO total_visits (userId) VALUES (?)");
    $stmt->bind_param("i", $userId);
  }
  if($stmt->execute() === false) {
      echo "<script>alert('Error: " . $stmt->error . "');</script>";
  }
  $_SESSION['siteVisited'] = true;
}

$conn->close();
?>