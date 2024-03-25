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
        $password = $_POST['passw'];

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
                $userName = $user['userName'];
                if (!$user['enabled']) {
                    echo "<script>alert('Your account $userName with email $email is disabled. Please contact an administrator to have your account reinstated.'); window.location.href = '../index.php';</script>";
                    exit();
                }
                // Password is correct, set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['userId'] = $user['userid'];
                $_SESSION['userName'] = $user['userName'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['profilePicture'] = $user['profilePicture'];
                $_SESSION['admin'] = ($user['admin']===0) ? false : true;
                

                if ($_SESSION['admin']) {
                    echo "<script>alert('Admin $userName with email $email has logged in successfully.'); window.location.href = '../index.php';</script>";
                } else {
                    echo "<script>alert('User $userName with email $email has logged in successfully.'); window.location.href = '../index.php';</script>";
                }
                
            } else {
                // Password is incorrect
                echo "<script>alert('Incorrect password'); window.history.back();</script>";

            }
        } else {
            // User with provided email not found
            echo "<script>alert('No user was found with the provided e-mail.'); window.history.back();</script>";
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
