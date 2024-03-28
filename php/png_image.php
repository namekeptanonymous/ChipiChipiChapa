<?php
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=db_24725301", "24725301", "24725301");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    if (isset($_GET['id'])) {
        $userId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

        $sql = "SELECT profilePicture FROM users WHERE userid = :userid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            header('Content-Type: image/png');
            echo $row['profilePicture'];
        } else {
            echo 'No user was found with the given user ID.';
        }
    } else {
        echo 'No ID was sent with the URL.';
    }
?>
