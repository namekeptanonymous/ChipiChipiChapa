<?php

session_start();

// Check if profile picture is set in the session
if (isset($_SESSION['profilePicture'])) {
    echo $_SESSION['profilePicture'];
} else {
    // If profile picture is not set, display a default icon
    echo '<span class="material-symbols-outlined">account_circle</span>';
}
?>
