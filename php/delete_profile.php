<?php
    session_start();
    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=db_24725301", "24725301", "24725301");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    if (isset($_GET['id'])) {
        if ($_SESSION['userId']==$_GET['id']) {
            echo "<script>alert('You cannot remove yourself.'); window.history.back();</script>";
            exit();
        }
        $userId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM users WHERE userid = :userid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() === 1) {
            echo "<script>alert('The user with the user ID $userId has been successfully deleted.'); window.history.back();</script>";
            exit();
        } else {
            echo "<script>alert('There was no user found with that user ID.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('There was no user ID provided.'); window.history.back();</script>";
    }
?>