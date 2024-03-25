<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head lang="en">
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="css/index.css" />
    <link rel="icon" type="image/x-icon" href="images/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChipiChipiChapa</title>
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-md">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="images/longbanner.png" height="38" class="d-inline-block align-top brand-image" alt=""></a>
            <div class="navbar-nav text-center d-flex align-items-center justify-content-center">
                <?php
                    // Check if profile picture data is available in session
                    if (isset($_SESSION['profilePicture'])) {
                        echo '';
                    } else {
                        echo '<a class="nav-link" href="pages/login.php">Login</a>or<a class="nav-link" href="pages/register.php">Register</a>';
                    }
                ?>
                <div class="dropdown <?php echo isset($_SESSION['userName']) ? '' : 'd-none'; ?>" id="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                        // Check if profile picture data is available in session
                        if (isset($_SESSION['profilePicture'])) {
                            // Use the display_image.php script as the src attribute
                            echo '<img src="php/display_image.php" height="24" alt="Profile Picture" class="material-symbols-outlined">';
                        } else {
                            // If profile picture data is not available, display a placeholder image or text
                            echo '<span class="material-symbols-outlined">account_circle</span>';
                        }
                    ?><?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : 'User'; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="pages/user_profile.php">User Profile</a></li>
                        <li><a class="dropdown-item" href="php/logout.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div id="main">
        <div class="container-fluid" id="splash">
            <p id="splash-text">
                <span>Chipi</span><span id="splash-text-blue">Chipi</span><span id="splash-text-maroon">Chapa</span>
            </p>
            <br><br>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">

                            Welcome to ChipiChipiChapa, the best place to find the best prices on Amazon products!<br><br>
                            Use the search bar to search for products by name or with an Amazon link!

                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <form class="form-inline" action="pages/productList.php" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control mr-sm-2" placeholder="Search" name="search" />
                                <button class="btn btn-outline-secondary my-2 my-sm-0 d-flex
                                    align-items-center justify-content-center" type="submit" style="padding: 6px">
                                    <span class="material-symbols-outlined">search</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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