<?php
    session_start();
    $returnUrl = isset($_GET['return']) ? $_GET['return'] : 'login.php'; // Default to login page
    session_destroy();
    session_start();
    $_SESSION['siteVisited'] = true;
    header("Location: " . $returnUrl);
    exit;
?>
