<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="icon" type="../image/x-icon" href="../images/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChipiChipiChapa</title>
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-md">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../images/longbanner.png" height="38" class="d-inline-block align-top brand-image" alt=""></a>
            <div class="navbar-nav text-center d-flex align-items-center justify-content-center">
                <?php
                    if (isset($_SESSION['profilePicture'])) {
                        echo '';
                    } else {
                        echo '<a class="nav-link" href="pages/login.php">Login</a>or<a class="nav-link" href="../pages/register.php">Register</a>';
                    }
                ?>
                <div class="dropdown <?php echo isset($_SESSION['userName']) ? '' : 'd-none'; ?>" id="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                        if (isset($_SESSION['profilePicture'])) {
                            echo '<img src="../php/display_image.php" height="24" alt="Profile Picture" class="material-symbols-outlined rounded-circle border">';
                        } else {
                            echo '<span class="material-symbols-outlined">account_circle</span>';
                        }
                    ?><?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : 'User'; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../pages/user_profile.php">User Profile</a></li>
                        <?php echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="../pages/manage_users.php">Manage Users</a></li>' : '';?>
                        <?php echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="../pages/inputData.php">Edit Product DB</a></li>' : '';?>
                        <li><a class="dropdown-item" href="../php/logout.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div id="main">
        <div class="container-fluid" id="splash">
            <h2>Price History Form</h2>
            <?php if ($_SESSION['admin']): ?>
            <form method="post" action="../php/addData.php">>
                <p>Product ID:</label><br>
                <input type="text" id="product_id" name="product_id" required><br><br>
                <label for="num_entries">Number of Entries:</label><br>
                <input type="number" id="num_entries" name="num_entries" min="1" required><br><br>
                <input type="submit" value="Add Entries">
            </form>
            <?php else: ?>
            <p>You do not have admin privileges.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer class="footer text-center py-3">
        <div class="container-fluid text-center" data-bs-theme="dark">
            <div class="row mt-3">
                <span class="text-muted">Work done by the ChipiChipiChapa team. All rights reserved.</span>
            </div>
        </div>
    </footer>
    <script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
</html>