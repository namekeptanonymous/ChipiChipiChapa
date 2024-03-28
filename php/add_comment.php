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
        $commentText = $_POST['commentText'];
        $userId = $_POST['userId'];
        $pid = $_POST['pid'];
        $time = date("Y-m-d H:i:s");
        if (!empty($commentText) ) {
            $stmt = $conn->prepare("INSERT INTO comments (pid, userid, commentText, timestamp) VALUES (?,?,?,?)");
            $stmt->bindParam(1, $pid, PDO::PARAM_INT);
            $stmt->bindParam(2, $userId, PDO::PARAM_INT);
            $stmt->bindParam(3, $commentText, PDO::PARAM_STR);
            $stmt->bindParam(4, $time, PDO::PARAM_STR);

            if($stmt->execute()){
                echo "<script>alert('Comment Added!'); window.location.href = '../pages/product.php?pid=$pid';</script>";
            }
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null;
?>
