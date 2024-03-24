<?php
    session_start();

    // Store the return URL if provided
    $returnUrl = isset($_GET['return']) ? $_GET['return'] : 'login.php'; // Default to login page if return URL not provided

    // Destroy the session
    session_destroy();

    // Redirect the user back to the stored URL
    header("Location: " . $returnUrl);
    exit;
?>
