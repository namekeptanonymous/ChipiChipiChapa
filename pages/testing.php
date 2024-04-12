<!DOCTYPE html>

<?php
session_start();
if (!isset($_SESSION['profilePicture'])) {
    header("Location: ../index.php");
    exit();
}

require_once "../php/log_page.php";
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
                        <input type="text" class="form-control mr-sm-2" placeholder="Search products" name="search"/>
                        <button class="btn btn-outline-secondary my-2 my-sm-0 d-flex
                        align-items-center justify-content-center" type="submit" style="padding: 6px">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </form>
                <?php
                    if (isset($_SESSION['profilePicture'])) {
                        echo '';
                    } else {
                        echo '<a class="nav-link disabled" href="#top">Login</a>or<a class="nav-link" href="./register.php">Register</a>';
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
                        <li><a class="dropdown-item" href="./user_profile.php">User Profile</a></li>
                        <li><a class="dropdown-item" href="./tracked.php">Tracked Products</a></li>
                        <?php
                        echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="./manage_users.php">Manage Users</a></li>' : '';
                        echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="./user_metrics.php">User Metrics</a></li>' : '';
                        echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="./inputData.php">Edit Product DB</a></li>' : '';
                        ?>
                        <li><a class="dropdown-item" href="../php/logout.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div id="main">
        <div class="container-fluid" id="splash">
            <h1 id="splash-text">Tracked Products</h1>
            <br><br>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <br><br>
                        <?php
                            $db = new mysqli('localhost', '24725301', '24725301', 'db_24725301');

                            if ($db->connect_error) {
                                die("Connection failed: " . $db->connect_error);
                            }
                            $stmt = $db->prepare("SELECT pid, timestamp FROM trackedProducts WHERE userId = ? ORDER BY timestamp DESC");
                            $stmt->bind_param("i", $userId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 0) {
                                echo "<br><p>No tracked products were found for the current user.</p>";
                            } else {
                                echo "<table class='table'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th> Image </th>";
                                        echo "<th> Product ID </th>";
                                        echo "<th> Product Name </th>";
                                        echo "<th> Date Tracked </th>";
                                        echo "<th> Current Price </th>";
                                        echo "<th> Link </th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while ($row = $result->fetch_assoc()) {
                                    $stmt2 = $db->prepare("SELECT name, image, url, price FROM products WHERE id = ?");
                                    $stmt2->bind_param("i", $row['pid']);
                                    $stmt2->execute();
                                    $result2 = $stmt2->get_result();
                                    $row2 = $result2->fetch_assoc();
                                    echo "<tr>";
                                        echo "<td>";
                                            echo "<img src=". $row2['image']. " width=50px height=50px>";
                                        echo "</td>";
                                        echo"<td>";
                                            echo "<a href='./product.php?pid=" . $row['pid'] . "'>" . $row['pid'] . "</a>";
                                        echo "</td>";
                                        echo "<td>";
                                            echo $row2['name'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $row['timestamp'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo '$' . $row2['price']. ' ';
                                        echo "</td>";
                                        echo "<td>";
                                            echo '<a href=' . $row2['url']. ' target="_blank">';
                                                echo '<input class="btn btn-primary" type="submit" value="Buy Here!">';
                                            echo '</a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                            }
                        ?>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <br>
                        <h1 id="splash-text">Comment History</h1>
                        <br><br>
                        <?php
                            $db = new mysqli('localhost', '24725301', '24725301', 'db_24725301');

                            if ($db->connect_error) {
                                die("Connection failed: " . $db->connect_error);
                            }
                            $stmt = $db->prepare("SELECT pid, commentText, timestamp FROM comments WHERE userId = ? ORDER BY timestamp DESC");
                            $stmt->bind_param("i", $userId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 0) {
                                echo "<br><p>No comments were found for the current user.</p>";
                            } else {
                                // Display the comment history table
                                echo "<table class='table'>";
                                echo "<thead><tr><th>Product ID</th><th>Comment</th><th>Timestamp</th></tr></thead>";
                                echo "<tbody>";
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>";
                                    echo "<a href='./product.php?pid=" . $row['pid'] . "'>" . $row['pid'] . "</a>";
                                    echo "</td><td>" . $row["commentText"] . "</td><td>" . $row["timestamp"] . "</td></tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                            }
                        ?>
                    </div>
                </div>

                <div class="row justify-content-center"> 
                <div class="col-md-12">
                    <br><br><br>
                    <h1 id="splash-text">Product Test</h1>
                    <?php
                            try {
                                $pdo = new PDO("mysql:host=localhost;dbname=db_24725301", "24725301", "24725301");
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            } catch (PDOException $e) {
                                die("Connection failed: " . $e->getMessage());
                            }
                    
                            $pid = 478398;
                            
                            $sql = 'SELECT * FROM products WHERE id LIKE :pid';
                            $stmt = $pdo->prepare($sql);
                            $stmt -> bindValue(':pid', "%" . $pid . '%');
                            $stmt->execute();

                            $sql_current_price = 'SELECT price FROM PriceHistory WHERE product_id = :pid ORDER BY date DESC LIMIT 1';
                            $stmt_current_price = $pdo->prepare($sql_current_price);
                            $stmt_current_price->bindValue(':pid', $pid);
                            $stmt_current_price->execute();
                            $current_price = $stmt_current_price->fetchColumn();

                            $sql_max_price = 'SELECT MAX(price) FROM PriceHistory WHERE product_id = :pid';
                            $stmt_max_price = $pdo->prepare($sql_max_price);
                            $stmt_max_price->bindValue(':pid', $pid);
                            $stmt_max_price->execute();
                            $max_price = $stmt_max_price->fetchColumn();

                            $sql_min_price = 'SELECT MIN(price) FROM PriceHistory WHERE product_id = :pid';
                            $stmt_min_price = $pdo->prepare($sql_min_price);
                            $stmt_min_price->bindValue(':pid', $pid);
                            $stmt_min_price->execute();
                            $min_price = $stmt_min_price->fetchColumn();

                            if($stmt->rowCount() > 0){
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

                                echo "<div class='row justify-content-center'>";
                                echo "<div class='col-sm-8'>";
                                echo "<canvas id='priceChart'></canvas>";
                                echo "</div>";
                                echo "<div class='col-sm-2'>";
                                echo "<br>";
                                echo "<select id='monthsSelector' class='form-select'>";
                                echo "<option value='1'>1 Month</option>";
                                echo "<option value='3'>3 Months</option>";
                                echo "<option value='6'>6 Months</option>";
                                echo "<option value='12'>12 Months</option>";
                                echo "</select>";
                                echo "<br>";
                                echo "<div class='row'>";
                                echo "<div class='col-sm-12'>";
                                echo "<p><b> Current Price: </b>$" . $current_price . "</p>";
                                echo "<p><b> Lowest Price: </b>$" . $min_price . "</p>";
                                echo "<p><b> Highest Price: </b>$" . $max_price . "</p>";
                                echo "<a href='". $row['url']."'><button type='button' class='btn btn-primary'>Buy On Bestbuy</button></a>" ;
                                echo "<br>";
                                echo "<br>";
                            if (isset($_SESSION['userId'])) {
                                echo"<input type='submit' value='Track This Product' class='btn btn-success' id='track'>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                                
                        } else {
                            echo "ERROR: ITEM NOT FOUND";
                        }
                    ?>
                    <br>
                    <br>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
        var ctx = document.getElementById('priceChart').getContext('2d');
        var chart;

        $('#monthsSelector').on('change', function() {
            var months = $(this).val();
            updateChart(months);
        });

        createChart(); 

        updateChart(1);

        var updateInterval = setInterval(function() {
            updateChart($('#monthsSelector').val()); 
        }, 1000);

        function updateChart(months) {
            $.ajax({
                url: '../php/update_chart.php?pid=<?php echo $pid; ?>&months=' + months,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var dates = response.dates.reverse();
                    var prices = response.prices.reverse();

                    var highestPrice = Math.max(...prices);
                    var lowestPrice = Math.min(...prices);
                    var currentPrice = prices[prices.length - 1];

                    chart.data.labels = dates;
                    chart.data.datasets[0].data = prices;
                    chart.update();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                }
            });
        }

        function createChart() {
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: '$ CAD',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        data: [] 
                    }]
                },
                options: {
                    animation: {
                        duration: 0 
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value, index, values) {
                                    return '$' + value.toFixed(2);
                                }
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Price History',
                            font: {
                                size: 20
                            },
                            color: 'black'
                        }
                    }
                }
            });
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
</html>