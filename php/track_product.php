<?php
session_start();


$servername = "localhost";
$username = "24725301";
$password = "24725301";
$database = "db_24725301";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_POST['userId'];
        $pid = $_POST['pid'];
        $time = date("Y-m-d H:i:s");
        
        $trackedChecked = 'SELECT userId FROM trackedproducts WHERE userId = :userId AND pid = :pid';
        $trackedStmt = $conn->prepare($trackedChecked);
        $trackedStmt->bindValue(':userId', $userId);
        $trackedStmt->bindValue(':pid', $pid); 
        $trackedStmt->execute();
        $row = $trackedStmt->fetch();

        if($row != null) {
            print("Tracker already added");
            return;
        }

        $stmt = $conn->prepare("INSERT INTO trackedproducts (pid, userid, timestamp) VALUES (?,?,?)");
        $stmt->bindParam(1, $pid, PDO::PARAM_INT);
        $stmt->bindParam(2, $userId, PDO::PARAM_INT);
        $stmt->bindParam(3, $time, PDO::PARAM_STR);

        if($stmt->execute()){
            echo "Tracker Added";
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null;
?>