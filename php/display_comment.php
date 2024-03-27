<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=bestbuy", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=chipichipichapa", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

# Get all comments
$pid = $_GET['pid'];
$sql = 'SELECT * FROM comments WHERE pid LIKE :pid';
$stmt = $conn->prepare($sql);
$stmt->bindValue(':pid', "%" . $pid . '%');
$stmt->execute();
while ($row = $stmt->fetch()) {
    $sql2 = 'SELECT userName FROM users WHERE userid LIKE :userid';
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindValue(':userid', '%' . $row['userid'] . '%');
    $stmt2->execute();
    $row2 = $stmt2->fetch();
    echo "<tr>";
    echo "<td> " . $row2['userName'] . "</td> ";
    echo "<td> " . $row['commentText'] . "</td> ";
    echo "<td> " . $row['timestamp'] . "</td> ";
    echo "</tr>";
}
?>