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
        $email = $_POST['email'];
        $userName = $_POST['name'];
        $password = password_hash($_POST['passw'], PASSWORD_DEFAULT);

        // Handle profile picture upload
        if(isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == UPLOAD_ERR_OK) {
            $profilePicture = file_get_contents($_FILES['profile-pic']['tmp_name']);
        }

        // Retrieve last inserted userId
        $stmt = $conn->query("SELECT MAX(userid) AS max_userid FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastUserId = ($result && isset($result['max_userid'])) ? $result['max_userid'] : 0;
        $newUserId = $lastUserId + 1;


        $stmt = $conn->prepare("INSERT INTO users (userid, email, profilePicture, userName, password, admin) VALUES (?, ?, ?, ?, ?, 0)");
        
        $stmt->bindParam(1, $newUserId, PDO::PARAM_INT);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->bindParam(3, $profilePicture, PDO::PARAM_LOB);
        $stmt->bindParam(4, $userName, PDO::PARAM_STR);
        $stmt->bindParam(5, $password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['logged_in'] = true;
            $_SESSION['userId'] = $newUserId;
            $_SESSION['userName'] = $userName;
            $_SESSION['email'] = $email;
            $_SESSION['profilePicture'] = $profilePicture;
            $_SESSION['admin'] = false;
            echo "<script>alert('User $userName has registered successfully.'); window.location.href = '../index.php';</script>";

        } else {
            echo "<script>alert('Error: Something went wrong. User registration failed, please check that you have entered a unique e-mail address that has never been used before.'); window.history.back();</script>";
        }

        // Close statement
        $stmt = null;
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<script>alert('Error: Something went wrong. User registration failed, please check that you have entered a unique e-mail address that has never been used before.'); window.history.back();</script>";
}

// Close connection
$conn = null;
?>
