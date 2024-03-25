<!DOCTYPE html>

<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=newdb", "root", "");
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
            <p id="splash-text" class="text-center">
                <span>Chipi</span><span id="splash-text-blue">Chipi</span><span id="splash-text-maroon">Chapa</span>
            </p>
            <br><br>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form class="form-inline">
                        <div class="input-group">
                            <input type="text" class="form-control mr-sm-2" name="search" placeholder="Search">
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                <?php
                                $sql = 'SELECT id, name FROM categories';
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                while ($row = $stmt->fetch()) {
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                            <select class="form-select" name="limit">
                                <option value="5">5 items per page</option>
                                <option value="10">10 items per page</option>
                                <option value="20">20 items per page</option>
                            </select>
                            <button class="btn btn-outline-secondary my-2 my-sm-0 align-items-center justify-content-center" type="submit">
                                <span class="material-symbols-outlined">search</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php
                    if(isset($_GET['search'])) {
                        $search = $_GET['search'];
                        $category = isset($_GET['category']) ? $_GET['category'] : ''; // Get selected category
                        $limit = isset($_GET['limit']) ? $_GET['limit'] : 5; 
                    
                        $sql = 'SELECT * FROM products WHERE name LIKE :search';
                        if (!empty($category)) {
                            // If a category is selected, add category filter to the query
                            $sql .= ' AND id IN (SELECT productId FROM productcategory WHERE categoryId = :category)';
                        }
                        $sql .= ' LIMIT :limit';
                    
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':search', '%' . $search . '%');
                        if (!empty($category)) {
                            $stmt->bindValue(':category', $category);
                        }
                        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                        $stmt->execute();

                        if($stmt->rowCount()>0 && !($search == "")){
                            echo "<div class='row row-cols-1 row-cols-md-2 row-cols-lg-3' id=results>";
                            while ($row = $stmt->fetch()) {
                                echo "<div class='col mb-4'>";
                                echo "<div class='card'>";
                                echo "<img src='" . $row['image'] . "' class='card-img-top' alt='" . $row['name'] . "'>";
                                echo "<div class='card-body'>";
                                echo "<h5 class='card-title'><a href='product.php?pid=" . $row['id'] . "'>" . $row['name'] . "</a></h5>";
                                echo "<p class='card-text'>Price: $" . $row['price'] . "</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                            echo "</div>";
                        } else if (!($search == "")) {
                            echo "<h3 class='text-center'>Sorry, no results for: " . $search . "</h3>";
                        }
                        

                    } else {
                        echo "<p class='text-center'>No search query provided.</p>";
                    }
                    ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
