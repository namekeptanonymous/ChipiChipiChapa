<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChipiChipiChapa</title>
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-md">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../images/longbanner.png" height="38" class="d-inline-block align-top brand-image" alt=""></a>
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

    <div id="main">
        <div class="container-fluid" id="splash">
            <p id="splash-text">
                <span>Chipi</span><span id="splash-text-blue">Chipi</span><span id="splash-text-maroon">Chapa</span>
            </p>
            <br><br>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                    <?php
                        try {
                            $pdo = new PDO("mysql:host=localhost;dbname=db_32030298", "root", "");
                            // Set PDO to throw exceptions
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            echo "connected <br>";
                        } catch (PDOException $e) {
                            die("Connection failed: " . $e->getMessage());
                        }

                        try {

                            // $stmt = $pdo->prepare("INSERT INTO products (pid, currPrice, dateOfChecked, highestPrice, lowestPrice) VALUES (:pid, :currPrice, :dateOfChecked, :highestPrice, :lowestPrice)");
                            // $stmt = $pdo->prepare("UPDATE products SET currPrice = :currPrice, dateOfChecked = :dateOfChecked, highestPrice = :highestPrice, lowestPrice = :lowestPrice WHERE pid = :pid");

                            // // Bind parameters
                            // $stmt->bindParam(':pid', $pid);
                            // $stmt->bindParam(':currPrice', $currPrice);
                            // $stmt->bindParam(':dateOfChecked', $dateOfChecked);
                            // $stmt->bindParam(':highestPrice', $highestPrice);
                            // $stmt->bindParam(':lowestPrice', $lowestPrice);

                            // // Set parameters
                            // $pid = 1;
                            // $currPrice = 1.99; // Example value
                            // $dateOfChecked = "2024-03-21 11:00:00"; // Example value
                            // $highestPrice = 100.99; // Example value
                            // $lowestPrice = 0.99; // Example value
                            // $stmt->execute();

                            //echo "New record inserted successfully<br>";
                        } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }

                        $stmt = $pdo->query('SELECT * FROM products');

                        while ($row = $stmt->fetch()) {
                            echo "Column1: " . $row['pid'] . " - Column2: " . $row['currPrice'] . " - Column3: " . $row['dateOfChecked'] . " - Column4: " . $row['highestPrice'] . " - Column5: " . $row['lowestPrice'] ."<br>";
                        }
                    ?>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <form class="form-inline">
                            <div class="input-group">
                                <input type="text" class="form-control mr-sm-2" placeholder="Search" />
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>
</html>