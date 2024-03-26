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
            <h1 id="splash-text">Manage Users</h1>
            <br><br>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form class="form-inline mb-3" action="" method="GET">
                            <div class="input-group">
                                <select class="form-select" name="search_type" style="width:5em">
                                    <option value="" selected disabled>Filter</option>
                                    <option value="username" <?php echo ($_GET['search_type']==='username') ? 'selected' : '';?>>Username</option>
                                    <option value="email" <?php echo ($_GET['search_type']==='email') ? 'selected' : '';?>>Email</option>
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
                    <div class="col-md-8">
                        <?php
                            try {
                                $pdo = new PDO("mysql:host=localhost;dbname=chipichipichapa", "root", "");
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            } catch (PDOException $e) {
                                die("Connection failed: " . $e->getMessage());
                            }

                            if (isset($_GET['search'])) {
                                if (isset($_GET['search_type'])) {
                                    if ($_GET['search_type']==="email") {
                                        $sql = 'SELECT * FROM users WHERE email LIKE "%' . $_GET['search'] . '%"';
                                    } else {
                                        $sql = 'SELECT * FROM users WHERE userName LIKE "%' . $_GET['search'] . '%"';
                                    }
                                } else {
                                    $sql = 'SELECT * FROM users WHERE userName LIKE "%' . $_GET['search'] . '%"';
                                }
                            } else {
                                echo 'Please search to be able to see users!';
                                $stopSearch = true;
                            }

                            if (!isset($stopSearch)) {
                                $stmt = $pdo->query($sql);
                                if($stmt->rowCount() > 0) {
                                    echo "<div class='row' id='user-cards'>";
                                    // Iterate over each user
                                    while ($row = $stmt->fetch()) {
                                        echo "<div class='col mb-4'>";
                                        echo "<div class='card" . (($row['enabled']) ? '' : ' disabled-card') . "'>";
                                        echo "<div class='card-body'>";
                                        
                                        $_SESSION['manageUserPicture'] = $row['profilePicture'];
                                        echo "<div class='d-flex align-items-center justify-content-center'>";
                                        echo "<img src='../php/png_image.php?id=" . $row['userid'] . "' class='rounded-circle border' alt='User Picture' height='50' style='margin-right: 0.5em'>";
                                        echo "<h5 class='card-title'>" . $row['userName'] . (($row['enabled']) ? '' : ' [disabled]') . "</h5>";
                                        // More user information here if needed
                                        echo "<a href='../php/disable_profile.php?id=" . $row['userid'] . "'><button class='btn btn-outline-danger my-2 my-sm-0 d-flex align-items-center justify-content-center' style='padding: 6px; margin: 0em 0.5em 0em 0.5em''>
                                            <span class='material-symbols-outlined'>power_settings_new</span>
                                        </button></a>";
                                        echo "<a href='../php/edit_profile.php?id=" . $row['userid'] . "'><button class='btn btn-outline-primary my-2 my-sm-0 d-flex align-items-center justify-content-center' style='padding: 6px; margin-right: 0.5em'>
                                            <span class='material-symbols-outlined'>edit</span>
                                        </button></a>";
                                        echo "<a href='../php/delete_profile.php?id=" . $row['userid'] . "'><button class='btn btn-outline-danger my-2 my-sm-0 d-flex align-items-center justify-content-center' style='padding: 6px'>
                                            <span class='material-symbols-outlined'>delete</span>
                                        </button></a>";
                                        echo "</div>";
    
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                } else {
                                    echo "<h3 class='text-center'>No users found.</h3>";
                                }
                            }
                        ?>
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

</body>
</html>