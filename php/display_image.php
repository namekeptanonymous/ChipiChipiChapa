<?php

session_start();

if (isset($_SESSION['profilePicture'])) { // Check if user logged
    echo $_SESSION['profilePicture'];
} else {
    echo '<span class="material-symbols-outlined">account_circle</span>';
}
?>
