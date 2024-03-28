<!DOCTYPE html>

<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: ../index.php");
    exit();
}
?>

<html>
<head lang="en">
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../css/manage_users.css" />
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>ChipiChipiChapa</title>
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-md">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php"><img src="../images/longbanner.png" height="38" class="d-inline-block align-top brand-image" alt="" /></a>
            <div class="navbar-nav text-center d-flex align-items-center justify-content-center">
                <form class="form-inline" action="./productList.php" method="get">
                    <div class="input-group">
                    <input type="text" class="form-control mr-sm-2" placeholder="Search" name="search"/>
                        <button class="btn btn-outline-secondary my-2 my-sm-0 d-flex
                        align-items-center justify-content-center" type="submit" style="padding: 6px">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </form>
                <?php
                    // Check if profile picture data is available in session
                    if (isset($_SESSION['profilePicture'])) {
                        echo '';
                    } else {
                        echo '<a class="nav-link disabled" href="#top">Login</a>or<a class="nav-link" href="./register.php">Register</a>';
                    }
                ?>
                
                <div class="dropdown <?php echo isset($_SESSION['userName']) ? '' : 'd-none'; ?>" id="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                        // Check if profile picture data is available in session
                        if (isset($_SESSION['profilePicture'])) {
                            // Use the display_image.php script as the src attribute
                            echo '<img src="../php/display_image.php" height="24" alt="Profile Picture" class="material-symbols-outlined rounded-circle border">';
                        } else {
                            // If profile picture data is not available, display a placeholder image or text
                            echo '<span class="material-symbols-outlined">account_circle</span>';
                        }
                    ?><?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : 'User'; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="./user_profile.php">User Profile</a></li>
                        <li><a class="dropdown-item" href="../php/logout.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div id="main">
    <div class="container-fluid" id="splash">
        <h1 id="splash-text">Manage Comments</h1>
        <br><br>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form class="form-inline mb-3" action="../pages/manage_users.php" method="GET">
                        <div class="input-group">
                            <select class="form-select" name="search_type" style="width:5em">
                                <option value="" selected disabled>Filter</option>
                                <option value="username" <?php echo (isset($_GET['search_type']) && $_GET['search_type']==='username') ? 'selected' : '';?>>Username</option>
                                <option value="email" <?php echo (isset($_GET['search_type']) && $_GET['search_type']==='email') ? 'selected' : '';?>>Email</option>
                            </select>
                            <input type="text" class="form-control" name="search"
                                placeholder="Search by name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                style="width: 50%">
                            <button class="btn btn-outline-success my-2 my-sm-0 d-flex align-items-center justify-content-center" type="submit">
                                <span class="material-symbols-outlined">search</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center">
            <div class="col-sm-8">
                    <table class="table">
                        <thead>
                        <th scope="col">Comment</th>
                        <th scope="col">Time Stamp</th>
                        <th scope="col">Product Page</th>
                        <th scope="col"></th>
                        </thead>
                        <tbody id="discussion">
                        </tbody>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {
            // Function to reload discussion thread
            var interval = 1000;
            function reloadDiscussion() {
                console.log("here");
                $.ajax({
                    url: '../php/display_user_comments.php?userId=<?php
                        $userId = $_GET['userId'];
                        echo"$userId";?>',
                    type: 'GET',
                    success: function(response) {
                        $('#discussion').html(response); // Update discussion content
                        setTimeout(reloadDiscussion, interval);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                    }
                });
            }setTimeout(reloadDiscussion, interval);

            // Initial load of discussion thread
            reloadDiscussion();
        });

</script>
</body>
</html>