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

        // Handle profile picture upload
        if(isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == UPLOAD_ERR_OK) {
            $profilePicture = file_get_contents($_FILES['profile-pic']['tmp_name']);
        } else {
            // Default profile picture if no picture uploaded
            $profilePicture = file_get_contents('default_profile_pic.png');
        }

        // Retrieve last inserted userId
        $stmt = $conn->query("SELECT MAX(userid) AS max_userid FROM users");
        $lastUserId = $stmt->fetch(PDO::FETCH_ASSOC)['max_userid'];
        $newUserId = $lastUserId + 1;

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO users (userid, email, profilePicture, userName) VALUES (?, ?, ?, ?)");
        
        // Bind parameters
        $stmt->bindParam(1, $newUserId, PDO::PARAM_INT);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->bindParam(3, $profilePicture, PDO::PARAM_LOB);
        $stmt->bindParam(4, $userName, PDO::PARAM_STR);

        // Execute SQL statement
        if ($stmt->execute()) {
            // User successfully registered, set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['userName'] = $userName;
            $_SESSION['profilePicture'] = $profilePicture;
            
            echo "New record created successfully";
        } else {
            echo "Error: Unable to execute statement";
        }

        // Close statement
        $stmt = null;
        
        // Redirect back to the referring page
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit; // Make sure to exit after the redirection
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Close connection
$conn = null;
?>
