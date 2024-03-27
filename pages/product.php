<!DOCTYPE html>

<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=bestbuy", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChipiChipiChapa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="../css/productList.css">
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-md navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img src="../images/longbanner.png" height="38" class="d-inline-block align-top brand-image" alt="">
            </a>
            <div class="navbar-nav text-center d-flex align-items-center justify-content-center">
                <?php
                    // Check if profile picture data is available in session
                    if (isset($_SESSION['profilePicture'])) {
                        echo '';
                    } else {
                        echo '<a class="nav-link" href="./login.php">Login</a>or<a class="nav-link" href="./register.php">Register</a>';
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
                        <?php echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="./manage_users.php">Manage Users</a></li>' : '';?>
                        <li><a class="dropdown-item" href="../php/logout.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div id="main" class="container-fluid">
        <div class="container-fluid" id="splash">
            
            <div class="row justify-content-center"> 
            <div class="col-md-8">
                    <?php
                        if (isset($_GET['pid'])) {
                            $pid = $_GET['pid'];
                            
                            # GET SQL
                            $sql = 'SELECT * FROM products WHERE id LIKE :pid';
                            $stmt = $pdo->prepare($sql);
                            $stmt -> bindValue(':pid', "%" . $pid . '%');
                            $stmt->execute();

                            if($stmt->rowCount() == 1){
                                $row = $stmt->fetch();
                                echo "<div class='row'>";
                                    echo "<div class='col-sm-4'>";
                                        echo "<img src='" . $row['image'] . "' class='card-img-top img-responsive' alt='" . $row['name'] . "'>"; 
                                    echo "</div>";
                                    echo "<div class='col-sm-2'>";

                                    echo "</div>";
                                    echo "<div class='col-sm-6'>";
                                        echo "<h2><b>". $row['name'] ."</b></h2>"; 
                                        echo "<br>";
                                        echo "<p> ". $row['description']." </p>"; 
                                    echo "</div>";
                                echo "</div>";
                                echo "<br>";

                                echo "<div class='row'>";
                                    echo "<div class='col-sm-4'>";
                                        echo "<a href='". $row['url']."'><button type='button' class='btn btn-primary'>Buy On Bestbuy</button></a>" ;
                                    echo "</div>";
                                    echo "<div class='col-sm-2'>";

                                    echo "</div>";
                                    echo "<div class='col-sm-6 '>";
                                        echo "<h2><b> Current Price: </b>$". $row['price']."</h2>";
                                        echo "<h2><b> Lowest Price: </b>$". $row['price']."</h2>";
                                        echo "<h2><b> Highest Price: </b>$". $row['price']."</h2>";
                                    echo "</div>";
                                echo "</div>";
                            } else {
                                echo "ERROR: ITEM NOT FOUND";
                            }
                            
                        }
                    ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
