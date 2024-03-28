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
        $commentId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM comments WHERE commentId = :commentId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() === 1) {
            echo "<script>alert('The comment with the comment ID $commentId has been successfully deleted.'); window.history.back();</script>";
            exit();
        } else {
            echo "<script>alert('There was no comment found with that comment ID.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('There was no comment ID provided.'); window.history.back();</script>";
    }
?>