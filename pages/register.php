<!DOCTYPE html>

<?php
session_start();
if (isset($_SESSION['profilePicture'])) {
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
    <link rel="stylesheet" href="../css/register.css" />
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
                    if (isset($_SESSION['profilePicture'])) {
                        echo '';
                    } else {
                        echo '<a class="nav-link" href="./login.php">Login</a>or<a class="nav-link disabled" href="./register.php">Register</a>';
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
                        echo ($_SESSION['admin']) ? '<li><a class="dropdown-item" href="./inputData.php">Edit Product DB</a></li>' : '';
                        ?>
                        <li><a class="dropdown-item" href="../php/logout.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div id="main">
        <div class="container-fluid text-center" id="register-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST" id="register-form" action="../php/register_script.php" enctype="multipart/form-data">
                        <fieldset>
                            <div class="card register-card">
                                <div class="card-body">
                                    <h3 class="card-title">Register</h3><br>
                                    <label for="name">Name:</label><br>
                                    <input type="text" id="name" name="name"><br><br>
                                    <label for="email">Email:</label><br>
                                    <input type="email" id="email" name="email" placeholder="example@gmail.com"><br><br>
                                    <label for="passw">Password:</label><br>
                                    <input type="password" id="passw" name="passw"><br><br>
                                    <label for="passw-rpt">Password (repeat):</label><br>
                                    <input type="password" id="passw-rpt" name="passw-rpt"><br><br>
                                    <label for="profile-pic" id="profile-pic-label">Profile Picture (file must be a .png):</label><br>
                                    <input type="file" id="profile-pic" name="profile-pic"><br><br>
                                    <input type="submit" value="Submit" class="btn btn-success" id="submit-btn">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <br>
    </div>
    <footer class="footer text-center py-3">
        <div class="container-fluid text-center" data-bs-theme="dark">
            <div class="row mt-3">
                <span class="text-muted">Work done by the ChipiChipiChapa team. All rights reserved.</span>
            </div>
        </div>
    </footer>
    <script>
        var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var email = document.getElementById("email");
        var passw = document.getElementById("passw");
        var passwRpt = document.getElementById('passw-rpt');
        var profilePic = document.getElementById("profile-pic");
        var submit_btn = document.getElementById("submit-btn");
        submit_btn.disabled = true;
        var email_flag = false;
        var passw_flag = false;
        var profile_pic_flag = false;

        email.addEventListener("blur", function(e) {
            if (!email_pattern.test(email.value) && !(email.value == null || email.value == "")) {
                email.style.border = "3px solid red";
                email.style.borderRadius = "0.25rem";
                email_flag = false;
                submit_btn.disabled = true;
            } else {
                email.style = "";
                email_flag = true;
                if (email_flag && passw_flag && profile_pic_flag) {
                    submit_btn.disabled = false;
                }
            }
        });
        passw.addEventListener("blur", function(e) {
            if (passw.value != null && passw.value != "") {
                passw.style = "";
                passw_flag = true;
                if (email_flag && passw_flag && profile_pic_flag) {
                    submit_btn.disabled = false;
                }
            } else {
                passw.style.border = "3px solid red";
                passw.style.borderRadius = "0.25rem";
                passw_flag = false;
                submit_btn.disabled = true;
            }
        });
        profilePic.addEventListener("blur", function(e) {
            if (profilePic.files.length > 0) {
                profile_pic_flag = true;
                if (email_flag && passw_flag && profile_pic_flag) {
                    submit_btn.disabled = false;
                }
            } else {
                profile_pic_flag = false;
                submit_btn.disabled = true;
            }
        });
        document.getElementById("register-form").onsubmit = function(e){
            if (email.value == null || email.value == "") {
                e.preventDefault();
                alert("Please enter an e-mail address.")
            } else if (!email_pattern.test(email.value)) {
                e.preventDefault();
                alert("Please enter a valid e-mail address.")
            } else if (passw.value == null || passw.value == "") {
                e.preventDefault();
                alert("Please enter a password.")
            } else if (profilePic.files.length === 0) {
                e.preventDefault();
                alert("Please select a profile picture.")
            } else if (passw.value !== passwRpt.value) {
                alert('The password and repeat password must match.');
                e.preventDefault();
                return;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
</html>