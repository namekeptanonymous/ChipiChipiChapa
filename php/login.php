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
        $password = $_POST['password']; // Assuming the password is sent from the form

        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        
        // Bind parameters
        $stmt->bindParam(1, $email, PDO::PARAM_STR);

        // Execute SQL statement
        $stmt->execute();

        // Check if a user with the provided email exists
        if ($stmt->rowCount() == 1) {
            // Fetch user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['userName'] = $user['userName'];
                $_SESSION['profilePicture'] = $user['profilePicture'];
            } else {
                // Password is incorrect
                echo "Incorrect password";
            }
        } else {
            // User with provided email not found
            echo "User not found";
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
