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
        $userName = $_POST['name'];
        $email = $_POST['email'];
        $new_password = $_POST['passw'];

        // Handle profile picture upload
        $profilePicture = null;
        if(isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == UPLOAD_ERR_OK) {
            $profilePicture = file_get_contents($_FILES['profile-pic']['tmp_name']);
        }

        // Check if all required fields are provided
        if (!empty($userName) || !empty($email) || !empty($new_password) || !empty($profilePicture)) {
            // Prepare SQL statement
            $sql = "UPDATE users SET ";
            $values = array();

            if (!empty($userName)) {
                $sql .= "userName = ?, ";
                $values[] = $userName;
                if (!isset($_GET['id']) && !$_SESSION['admin']) {
                    $_SESSION['userName'] = $userName;
                }
            }

            if (!empty($new_password)) {
                $sql .= "password = ?, ";
                $values[] = password_hash($new_password, PASSWORD_DEFAULT);
            }

            if (!empty($profilePicture)) {
                $sql .= "profilePicture = ?, ";
                $values[] = $profilePicture;
                if (!isset($_GET['id']) && !$_SESSION['admin']) {
                    $_SESSION['profilePicture'] = $profilePicture;
                }
            }

            // Remove trailing comma and space
            $sql = rtrim($sql, ", ");
            
            // Add condition for email
            $sql .= " WHERE userid = ?";
            if (isset($_GET['id']) && isset($_SESSION['admin']) && $_SESSION['admin']) {
                $values[] = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            } else {
                $values[] = $_SESSION['userId'];
            }

            // Prepare and execute SQL statement
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);

            if (isset($_GET['id']) && isset($_SESSION['admin']) && $_SESSION['admin']) {
                echo "<script>alert('The profile details were modified successfully.'); window.location.href = '../pages/manage_users.php';</script>";
            } else {
                echo "<script>alert('The profile details were modified successfully.'); window.location.href = '../pages/user_profile.php';</script>";
            }
            
        } else {
            // Some required fields are missing
            if (isset($_GET['id']) && isset($_SESSION['admin']) && $_SESSION['admin']) {
                echo "<script>alert('There was an error changing profile details. Perhaps the account was deleted?'); window.location.href = '../pages/manage_users.php';</script>";
            } else {
                echo "<script>alert('There was an error changing profile details. Perhaps the account was deleted?'); window.location.href = '../pages/user_profile.php';</script>";
            }
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Close connection
$conn = null;
?>
