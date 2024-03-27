<?php
    // Establish database connection
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=chipichipichapa", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Check if the 'id' parameter is present in the query string
    if (isset($_GET['id'])) {
        // Sanitize the ID parameter to prevent SQL injection
        $userId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

        // Query to retrieve the profile picture based on user ID
        $sql = "SELECT profilePicture FROM users WHERE userid = :userid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Check if a record is found
        if ($stmt->rowCount() > 0) {
            // Fetch the profile picture
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            header('Content-Type: image/png');
            echo $row['profilePicture'];
        } else {
            // Output a default image or an error message if user ID is not found
            echo '';
        }
    } else {
        // Output a default image or an error message if 'id' parameter is not provided
        echo '';
    }
?>
