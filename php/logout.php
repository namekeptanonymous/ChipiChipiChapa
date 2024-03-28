<?php
    session_start();
    $returnUrl = isset($_GET['return']) ? $_GET['return'] : 'login.php'; // Default to login page
    session_destroy();
    header("Location: " . $returnUrl);
    exit;
?>
