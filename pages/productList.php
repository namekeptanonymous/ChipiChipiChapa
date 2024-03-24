<!DOCTYPE html>
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
            <a class="navbar-brand" href="../index.html">
                <img src="../images/longbanner.png" height="38" class="d-inline-block align-top brand-image" alt="">
            </a>
            <div class="navbar-nav text-center d-flex align-items-center justify-content-center">
                <a class="nav-link" href="../pages/login.html">Login</a>or<a class="nav-link" href="../pages/register.html">Register</a>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-symbols-outlined">account_circle</span>User
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">User Settings</a></li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
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
                            <select class="form-select" name="limit">
                                <option value="5">5 items per page</option>
                                <option value="10">10 items per page</option>
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
                        $limit = isset($_GET['limit']) ? $_GET['limit'] : 5; // Default limit is 5
                        // Process the search query
                        // Perform database queries, etc.
                        echo "<h2 class='text-center'>Search results for: " . $search . " (Limit: " . $limit . ")</h2>";
                        try {
                            $pdo = new PDO("mysql:host=localhost;dbname=chipichipichapa", "root", "");
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            echo "connected <br>";
                        } catch (PDOException $e) {
                            die("Connection failed: " . $e->getMessage());
                        }

                        // Fetch products based on search query and limit
                        $sql = 'SELECT * FROM products WHERE productName LIKE :search LIMIT :limit';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':search', '%' . $search . '%');
                        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                        $stmt->execute();

                        echo "<div class='row row-cols-1 row-cols-md-2 row-cols-lg-3'>";
                        while ($row = $stmt->fetch()) {
                            echo "<div class='col mb-4'>";
                            echo "<div class='card'>";
                            echo "<img src='../images/" . $row['imgPath'] . "' class='card-img-top' alt='" . $row['productName'] . "'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'><a href='product.php?pid=" . $row['pid'] . "'>" . $row['productName'] . "</a></h5>";
                            echo "<p class='card-text'>Current Price: $" . $row['currPrice'] . "</p>";
                            echo "<p class='card-text'>Highest Price: $" . $row['highestPrice'] . "</p>";
                            echo "<p class='card-text'>Lowest Price: $" . $row['lowestPrice'] . "</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo "</div>";
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
