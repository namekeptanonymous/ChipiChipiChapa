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
        $email = $_POST['email'];
        $userName = $_POST['name'];
        $password = password_hash($_POST['passw'], PASSWORD_DEFAULT);

        // Handle profile picture upload
        if(isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == UPLOAD_ERR_OK) {
            $profilePicture = file_get_contents($_FILES['profile-pic']['tmp_name']);
        } else {
            // Default profile picture if no picture uploaded
            $profilePicture = file_get_contents('default_profile_pic.png');
        }

        // Retrieve last inserted userId
        $stmt = $conn->query("SELECT MAX(userid) AS max_userid FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastUserId = ($result && isset($result['max_userid'])) ? $result['max_userid'] : 0;
        $newUserId = $lastUserId + 1;


        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO users (userid, email, profilePicture, userName, password, admin) VALUES (?, ?, ?, ?, ?, 0)");
        
        // Bind parameters
        $stmt->bindParam(1, $newUserId, PDO::PARAM_INT);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->bindParam(3, $profilePicture, PDO::PARAM_LOB);
        $stmt->bindParam(4, $userName, PDO::PARAM_STR);
        $stmt->bindParam(5, $password, PDO::PARAM_STR);

        // Execute SQL statement
        if ($stmt->execute()) {
            // User successfully registered, set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['userName'] = $userName;
            $_SESSION['profilePicture'] = $profilePicture;
            
            echo "<script>alert('User $userName has registered successfully.'); window.location.href = '../index.php';</script>";

        } else {
            echo "<script>alert('Error: Something went wrong. User registration failed, please check that you have entered all of the requested information.'); window.location.href = '../index.php';</script>";
        }

        // Close statement
        $stmt = null;
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Close connection
$conn = null;
?>
