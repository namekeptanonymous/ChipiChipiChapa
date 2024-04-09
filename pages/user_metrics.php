<!DOCTYPE html>

<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: ../index.php");
    exit();
}
require_once "../php/log_page.php";

$db = new mysqli('localhost', '24725301', '24725301', 'db_24725301');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>

<html>
<head lang="en">
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="../css/user_metrics.css" />
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChipiChipiChapa</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                            // If profile picture data is not available, display a placeholder
                            echo '<span class="material-symbols-outlined">account_circle</span>';
                        }
                    ?><?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : 'User'; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="./user_profile.php">User Profile</a></li>
                        <?php
                        echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="./manage_users.php">Manage Users</a></li>' : '';
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
            <h1 id="splash-text">User Metrics</h1>
            <br><br>
            <div class="container-fluid">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-8">
                        <?php
                            // Query to get the number of entries for each page
                            $query1 = "SELECT page_name, COUNT(*) as count FROM page_visits GROUP BY page_name ORDER BY count DESC";
                            $result1 = $db->query($query1);

                            echo "<h2>Page Visits</h3><br>";
                            echo "<table class='table'>";
                            echo "<thead><tr><th>Page</th><th>Visits</th></tr></thead>";
                            echo "<tbody>";
                            while($row = $result1->fetch_assoc()) {
                                echo "<tr><td>" . $row["page_name"] . "</td><td>" . $row["count"] . "</td></tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        ?>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center">
                    <div class="col-md-8">
                        <br><h2>User Visits</h3><br>
                        <?php
                            // Query to get the number of pages for each user
                            $query2 = "SELECT userId, COUNT(*) as count FROM page_visits GROUP BY userId";
                            $result2 = $db->query($query2);

                            echo "<table class='table'>";
                            echo "<thead><tr><th>User</th><th>Pages visited</th><th>Total unique visits</th></tr></thead>";
                            echo "<tbody>";
                            while($row = $result2->fetch_assoc()) {
                                if ($row['userId']) {
                                    $query3 = "SELECT * FROM users WHERE userId=" . $row['userId'];
                                    $result3 = $db->query($query3);
                                    $userDetails = $result3->fetch_assoc();

                                    // Query to get the total visits for the current user
                                    $query4 = "SELECT COUNT(*) as count FROM total_visits WHERE userId=" . $row['userId'] . " GROUP BY userId";
                                    $result4 = $db->query($query4);
                                    $totalVisits = $result4->fetch_assoc()['count'];

                                    echo "<tr><td class='d-flex align-items-center justify-content-center'>";
                                    echo "<img src='../php/png_image.php?id=" . $row['userId'] . "' class='rounded-circle border' alt='User Picture' height='35' style='margin-right: 0.5em'>";
                                    echo "<a href='../pages/manage_users.php?userId=" . $row['userId'] . "'</a>" . $userDetails['userName'] . (($userDetails['enabled']) ? '' : ' [disabled]') . "</a>";
                                    echo "</td><td>" . $row["count"] . "</td><td>" . ($totalVisits ? $totalVisits : 0) . "</td></tr>";
                                } else {
                                    // Query to get the total visits for unregistered users
                                    $query4 = "SELECT COUNT(*) as count FROM total_visits WHERE userId IS NULL GROUP BY userId";
                                    $result4 = $db->query($query4);
                                    $totalVisits = $result4->fetch_assoc()['count'];

                                    echo "<tr><td>Unregistered users</td><td>" . $row["count"] . "</td><td>" . ($totalVisits ? $totalVisits : 0) . "</td></tr>";
                                }
                            }
                            echo "</tbody>";
                            echo "</table>";


                            $db->close();
                        ?>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center">
                    <div class="col-md-8">
                        <br><h2>Usage Charts</h3><br>
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group" style="scale: 70%;">
                            <input type="radio" class="btn-check" name="btnradio" id="page-visits" value="page_visits" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="page-visits">Page Visits</label>

                            <input type="radio" class="btn-check" name="btnradio" id="total-visits" value="total_visits" autocomplete="off">
                            <label class="btn btn-outline-primary" for="total-visits">User Visits</label>
                        </div>
                        <canvas id='usageChart'></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    $(document).ready(function() {
        var ctx = document.getElementById('usageChart').getContext('2d');
        var chart;

        var radioButtons = document.querySelectorAll('input[name="btnradio"]');
        var radioPick = document.querySelector('input[name="btnradio"]:checked').value;
        // Add a change event listener to each radio button
        for (var i = 0; i < radioButtons.length; i++) {
            radioButtons[i].addEventListener('change', function() {
                radioPick = document.querySelector('input[name="btnradio"]:checked').value;
                if (chart) {
                    chart.destroy();
                }
                createChart(); 
                updateChart();
            });
        }

        createChart(); 
        updateChart();

        var updateInterval = setInterval(function() {
            updateChart(); 
        }, 1000);

        function updateChart() {
            $.ajax({
                url: '../php/update_usage_charts.php?table='+radioPick,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var dates = response.dates.reverse();
                    var visits = response.visits.reverse();

                    chart.data.labels = dates;
                    chart.data.datasets[0].data = visits;
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
                        label: 'Total Visits',
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
                            beginAtZero: true,
                            ticks: {
                                precision: 0, // To ensure that only integer values are shown on the y-axis
                                callback: function(value, index, values) {
                                    return value.toFixed(0); // To remove decimal points
                                }
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: (radioPick === "page_visits") ? "Total Page Visits Per Day" : "Total Unique User Visits Per Day",
                            font: {
                                size: 20,
                                weight: 300
                            },
                            color: 'black'
                        }
                    }
                }
            });
        }
    });
    </script>

</body>
</html>