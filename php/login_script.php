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
        $password = $_POST['passw'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                $userName = $user['userName'];
                if (!$user['enabled']) {
                    echo "<script>alert('Your account $userName with email $email is disabled. Please contact an administrator to have your account reinstated.'); window.location.href = '../index.php';</script>";
                    exit();
                }
                $_SESSION['logged_in'] = true;
                unset($_SESSION['siteVisited']);
                $_SESSION['userId'] = $user['userid'];
                $_SESSION['userName'] = $user['userName'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['profilePicture'] = $user['profilePicture'];
                $_SESSION['admin'] = $user['admin'];
            
                $stmt = $conn->prepare("INSERT INTO user_logins_regs (userId, action) VALUES (?, 'login')");
                $stmt->bindParam(1, $user['userid'], PDO::PARAM_INT);
                $stmt->execute();
            
                if ($_SESSION['admin']) {
                    echo "<script>alert('Admin $userName with email $email has logged in successfully.'); window.location.href = '../index.php';</script>";
                } else {
                    echo "<script>alert('User $userName with email $email has logged in successfully.'); window.location.href = '../index.php';</script>";
                }
            } else {
                echo "<script>alert('Incorrect password'); window.history.back();</script>";
            }
            
        } else {
            echo "<script>alert('No user was found with the provided e-mail.'); window.history.back();</script>";
        }
        $stmt = null;
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null;
?>
