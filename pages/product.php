<!DOCTYPE html>

<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_24725301", "24725301", "24725301");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

require_once "../php/log_page.php";
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-md navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img src="../images/longbanner.png" height="38" class="d-inline-block align-top brand-image" alt="">
            </a>
            <div class="navbar-nav text-center d-flex align-items-center justify-content-center">
                <?php
                    if (isset($_SESSION['profilePicture'])) {
                        echo '';
                    } else {
                        echo '<a class="nav-link" href="./login.php">Login</a>or<a class="nav-link" href="./register.php">Register</a>';
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
                        <?php
                        echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="./manage_users.php">Manage Users</a></li>' : '';
                        echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="./user_metrics.php">User Metrics</a></li>' : '';
                        echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="../pages/inputData.php">Edit Product DB</a></li>' : '';
                        ?>
                        <li><a class="dropdown-item" href="../php/logout.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div id="main" class="container-fluid">
        <div class="container-fluid" id="splash">
            
            <div class="row justify-content-center"> 
            <div class="col-md-12">
                    <?php
                        if (isset($_GET['pid'])) {
                            $pid = $_GET['pid'];
                            
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
                        }
                    ?>
                    <br>
                    <br>
            </div>
            <div class="row justify-content-center"> 
                <div class="col-sm-4">
                    <?php
                        if (isset($_SESSION['userId'])) {
                            echo "<form method='POST' id='commentSubmit' action='../php/add_comment.php' enctype='multipart/form-data'>";
                            echo"<label for='commentText'>Comment:</label><br>";
                            echo"<input type='text' name='commentText'><br><br>";
                            echo"<input type='hidden' name='userId' value='". $_SESSION['userId']."' />";
                            echo"<input type='hidden' name='pid' value='".$_GET['pid']."' />";
                            echo"<input type='submit' value='Submit' class='btn btn-success' id='submit-btn'>";
                            echo "</form>";
                        }
                    ?>
                </div>
                <div class="col-sm-8">
                    <table class="table">
                        <thead>
                        <th scope="col">Username</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Date Posted</th>
                        </thead>
                        <tbody id="discussion"></tbody>
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
    <script>
        document.getElementById('commentSubmit').addEventListener('submit', function(event){
            var comment = document.getElementById('commentText').value;
            if(!comment){
                alert("Empty comment field");
                event.preventDefault;
                return;
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            var interval = 1000;
            function reloadDiscussion() {
                $.ajax({
                    url: '../php/display_comment.php?pid=<?php
                        $pid = $_GET['pid'];
                        echo"$pid";?>',
                    type: 'GET',
                    success: function(response) {
                        $('#discussion').html(response);
                        setTimeout(reloadDiscussion, interval);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                    }
                });
            }setTimeout(reloadDiscussion, interval);
            reloadDiscussion();
        });
    </script>

    <script>
        $(document).ready(function(){
            $("#track").click(function(){
                console.log("Track product");

                $.ajax({
                    type: "POST",
                    url: "../php/track_product.php",
                    // Userid and pid
                    data: {<?php echo'userId: '.$_SESSION["userId"].', pid: '.$_GET["pid"].' '?>},
                    success: function(response){
                        alert(response);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
