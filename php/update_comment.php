<?php
session_start();

$servername = "localhost";
$username = "24725301";
$password = "24725301";
$database = "db_24725301";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['admin']) && $_SESSION['admin']) {
        $commentText = $_POST['newText'];
        $commentId = $_POST['commentId'];
        
        if (!empty($commentText) && !empty($commentId)) {
            if ($commentId != 0) {
                $stmt = $conn->prepare("UPDATE comments SET commentText = ? WHERE commentId = ?");
                $stmt->bindParam(1, $commentText, PDO::PARAM_STR);
                $stmt->bindParam(2, $commentId, PDO::PARAM_INT);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Comment $commentId updated!'); window.location.href = '../pages/product.php?pid=$pid';</script>";
            } else {
                echo "<script>alert('Failed to update comment $commentId!');</script>";
            }
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Close connection
$conn = null;
?>
