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
    <div class="container-fluid" id="splash">
        <p id="splash-text" class="text-center">
            <span>Chipi</span><span id="splash-text-blue">Chipi</span><span id="splash-text-maroon">Chapa</span>
        </p>
        <br><br>
        <div class="row justify-content-center">
            <div class="col-md-6 d-flex justify-content-between"> <!-- Added d-flex and justify-content-between classes -->
                <div>
                    <!-- Filter Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        Filter
                    </button>
                </div>
                <div>
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" name="search" placeholder="Search" id="searchInput">
                        <button type="submit" class="btn btn-outline-success">Search</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Filter Modal -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Products</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="filterForm">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select class="form-select" name="category" id="category">
                                            <option value="">All Categories</option>
                                            <?php
                                            $sql = 'SELECT DISTINCT c.id, c.name FROM categories c JOIN subcategories sc ON c.id = sc.categoryId';
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute();
                                            while ($row = $stmt->fetch()) {
                                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subcategory" class="form-label">Subcategory</label>
                                        <select class="form-select" name="subcategory" id="subcategory">
                                            <option value="">Select a Category First</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="limit" class="form-label">Items per page</label>
                                        <select class="form-select" name="limit" id="limit">
                                            <option value="5">5 items per page</option>
                                            <option value="10">10 items per page</option>
                                            <option value="20">20 items per page</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
    <div class="row justify-content-center">
        <div class="col-md-12">
            <?php
            if(isset($_GET['search'])) {
                $search = $_GET['search'];
                $category = isset($_GET['category']) ? $_GET['category'] : ''; // Get selected category
                $limit = isset($_GET['limit']) ? $_GET['limit'] : 20; 
                
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
                    echo "<div class='row row-cols-2 row-cols-md-3 row-cols-lg-5' id=results>";
                    while ($row = $stmt->fetch()) {
                        echo "<div class='col mb-4'>";
                        echo "<div class='card'>";
                        echo "<img src='" . $row['image'] . "' class='card-img-top' alt='" . $row['name'] . "'>";
                        echo "<div class='card-body'>";
                        $title = (strlen($row['name']) > 50) ? substr($row['name'], 0, 50) . "..." : $row['name'];
                        echo "<h5 class='card-title'><a href='product.php?pid=" . $row['id'] . "'>" . $title . "</a></h5>";
                        echo "<p class='card-text'>$" . number_format($row['price'], 2) . "</p>";
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

    <footer class="footer text-center py-3">
        <div class="container-fluid text-center" data-bs-theme="dark">
            <div class="row mt-3">
                <span class="text-muted">Work done by the ChipiChipiChapa team. All rights reserved.</span>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
    document.getElementById('category').addEventListener('change', function() {
        var categoryId = this.value;
        if (categoryId !== '') {
            var formData = new FormData();
            formData.append('categoryId', categoryId);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById('subcategory').innerHTML = xhr.responseText;
                    } else {
                        console.error('Request failed:', xhr.status);
                    }
                }
            };
            xhr.open('POST', 'getSubcategories.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Set the correct header
            xhr.send(formData);
        }
    });

    function applyFilters() {
        //build url
        var search = document.getElementById('searchInput').value;
        var category = document.getElementById('category').value;
        var limit = document.getElementById('limit').value;
        var selectedSubcategory = document.getElementById('subcategory').value;
        const urlParams = new URLSearchParams(window.location.search);
        const existingSearch = urlParams.get('search');
        const existingCategory = urlParams.get('category');
        const existingLimit = urlParams.get('limit');

        if (search === '') {
            search = existingSearch || '';
        }
        if (category === '') {
            category = existingCategory || '';
        }
        if (limit === '') {
            limit = existingLimit || '20'; 
        }

        var url = 'productList.php?search=' + encodeURIComponent(search) + '&category=' + encodeURIComponent(selectedSubcategory || category) + '&limit=' + encodeURIComponent(limit);
        window.location.href = url;
    }

    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault();
        applyFilters();
    });

    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault();
        applyFilters();
    });

</script>
</body>
</html>