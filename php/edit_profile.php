<!DOCTYPE html>

<?php
    session_start();
    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header("Location: ../index.php");
        exit();
    }

    // Establish database connection
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=chipichipichapa", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    if (isset($_GET['id'])) {
        if ($_SESSION['userId']==$_GET['id']) {
            echo "<script>window.location.href = '../pages/user_profile.php';</script>";
            exit();
        }
        // Sanitize the ID parameter to prevent SQL injection
        $userId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        echo "<script>alert('There was no user ID provided.'); window.history.back();</script>";
        exit();
    }
?>

<html>
<head lang="en">
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../css/user_profile.css" />
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChipiChipiChapa</title>
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-md">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php"><img src="../images/longbanner.png" height="38" class="d-inline-block align-top brand-image" alt="" /></a>
            <div class="navbar-nav text-center d-flex align-items-center justify-content-center">
                <form class="form-inline" action="../pages/productList.php" method="get">
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
                        echo '<a class="nav-link" href="../pages/login.php">Login</a>or<a class="nav-link" href="./register.php">Register</a>';
                    }
                ?>
                
                <div class="dropdown <?php echo isset($_SESSION['userName']) ? '' : 'd-none'; ?>" id="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                        // Check if profile picture data is available in session
                        if (isset($_SESSION['profilePicture'])) {
                            // Use the display_image.php script as the src attribute
                            echo '<img src="./display_image.php" height="24" alt="Profile Picture" class="material-symbols-outlined rounded-circle border">';
                        } else {
                            // If profile picture data is not available, display a placeholder image or text
                            echo '<span class="material-symbols-outlined">account_circle</span>';
                        }
                    ?><?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : 'User'; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../pages/user_profile.php">User Profile</a></li>
                        <?php echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="../pages/manage_users.php">Manage Users</a></li>' : '';?>
                        <li><a class="dropdown-item" href="./logout.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div id="main">
        <div class="container-fluid" id="splash">
            <h1 id="splash-text">Edit Profile #<?php echo $_GET['id'];?></h1>
            <br><br>
            <div class="container-fluid">
                <p><b>Modify profile details:</b></p>
                <form method="POST" id="profile-change" action="../php/change_profile.php?id=<?php echo $_GET['id'];?>" enctype="multipart/form-data">
                    <fieldset>
                        <label for="name">Name:</label><br>
                        <input type="text" id="name" name="name"><br><br>

                        <label for="passw"> New Password:</label><br>
                        <input type="password" id="passw" name="passw"><br><br>

                        <label for="passw-rpt">New Password (repeat):</label><br>
                        <input type="password" id="passw-rpt" name="passw-rpt"><br><br>

                        <label for="profile-pic" id="profile-pic-label">Profile Picture:</label><br>
                        <input type="file" id="profile-pic" name="profile-pic"><br><br>
            
                        <input type="submit" value="Submit" class="btn btn-success" id="submit-btn">
                    </fieldset>
                </form>
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
        document.getElementById('profile-change').addEventListener('submit', function(event) {
            var name = document.getElementById('name').value;
            var passw = document.getElementById('passw').value;
            var passwRpt = document.getElementById('passw-rpt').value;
            var profilePicture = document.getElementById('profile-pic').value;

            // Check if any field is empty
            if (!name && !passw && !passwRpt && !profilePicture) {
                alert('No fields were filled in, please fill in at least one type of field(s).');
                event.preventDefault();
                return;
            }

            if (passw !== passwRpt) {
                alert('New password and repeat password must match.');
                event.preventDefault();
                return;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>
</html>