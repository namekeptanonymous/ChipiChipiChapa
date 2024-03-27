<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "chipichipichapa";


try {
    // Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Set parameters
        $commentText = $_POST['commentText'];
        $userId = $_POST['userId'];
        $pid = $_POST['pid'];
        $time = date("Y-m-d H:i:s");

        // Check if all required fields are provided
        if (!empty($commentText) ) {
            // Prepare SQL statement
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

// Close connection
$conn = null;
?>
