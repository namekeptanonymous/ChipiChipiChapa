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
                    <form class="form-inline mb-3" action="../pages/manage_users.php" method="GET">
                        <div class="input-group">
                            <select class="form-select" name="search_type" style="width:5em">
                                <option value="" selected disabled>Filter</option>
                                <option value="username" <?php echo (isset($_GET['search_type']) && $_GET['search_type']==='username') ? 'selected' : '';?>>Username</option>
                                <option value="email" <?php echo (isset($_GET['search_type']) && $_GET['search_type']==='email') ? 'selected' : '';?>>Email</option>
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

                    $userId = $_GET['userId'];

                    try {
                        $pdo = new PDO("mysql:host=localhost;dbname=chipichipichapa", "root", "");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Fetch user's comments from the database
                        $sql = $pdo->prepare("SELECT * FROM comments WHERE userId = ?");
                        $sql->execute([$userId]);
                    } catch(PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }

                    if ($sql->rowCount() > 0) {
                        echo "<table class='table'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Comment</th>";
                        echo "<th>Timestamp</th>";
                        echo "<th>Product ID</th>";
                        echo "<th></th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        // Iterate over each comment
                        while ($comment = $sql->fetch()) {
                            echo "<tr>";
                            // Comment text with larger font size
                            echo "<td style='font-size: 1.5rem;' onclick='editComment(this, " . $comment['commentId'] . ")'>" . $comment['commentText'] . "</td>";
                            // Timestamp
                            echo "<td style='font-size: 1rem;'>" . $comment['timestamp'] . "</td>";
                            // Product ID with link
                            echo "<td style='font-size: 1rem;'><a href='../pages/product.php?pid=" . $comment['pid'] . "'>" . $comment['pid'] . "</a></td>";
                            // Delete button
                            echo "<td>";
                            echo "<form action='delete_comment.php?id=" . $comment['commentId'] ."' method='POST'>";
                            echo "<td class='delete'><a href='../php/delete_comment.php?id=" . $comment['commentId'] . "' style='text-decoration: none'>
                                <button class='btn btn-outline-danger my-2 my-sm-0 d-flex align-items-center justify-content-center' style='padding: 6px'>
                                <span class='material-symbols-outlined'>delete</span></button></a></td>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<h3 class='text-center'>No comments found.</h3>";
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

    <script>
        function editComment(element, commentid) {
        var newText = prompt("Enter new comment text:", element.innerText.trim());
        if (newText !== null) {
            $.ajax({
                url: '/update_comment.php',
                method: 'POST',
                data: { commentId: commentid, newText: newText },
                success: function(response) {
                    if (response === 'success') {
                        element.innerText = newText;
                        alert('Comment $commentId updated!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while updating the comment.' + error);
                }
            });
        }
    }
</script>

</body>
</html>