<?php
session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include ("$root/configs/config.php");

try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
        echo "<p>Error connecting to database!</p>" . $e;
}
if (isset($_SESSION["loggedIn"])){
    if($_SESSION["loggedIn"]) {

    } else {
        header("Location: /admin/login.php");
        exit();
    }
} else {
    $_SESSION["loggedIn"] = false;
    header("Location: /admin/login.php");
    exit();
}

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    /* https://stackoverflow.com/questions/6768793/get-the-full-url-in-php - ax. */
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Page</title>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/materialize.min.js"></script>
    <link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
    <link href="/css/primary.css" rel="stylesheet" type="text/css">
    <style>
    .logout-btn {
        margin-right:15px;
    }
    .title {
        margin-left:15px;
    }
    .clear-top{
        margin-top: 70px;
    }
    </style>
</head>
<body>
<nav class="light-green darken-3">
    <span>
        <h5 class="float-left title isplay-inline-block">
            UrSh Admin
        </h5>
    </span>
    <span class="float-right logout-btn"><a href="/admin/logout.php">Logout</a></span>
    <span class="float-none"></span>
</nav>
    <div class="card clear-top" style="margin-top:80px;">
        <div class="card-content">
            <span class="card-title">Create new Url</span>
            <form method="POST" action="createlinkhandler.php">
            <?php
                if(isset($_SESSION['err'])){
                    switch ($_SESSION['err']) {
                        case 0:
                            break;
                        case 3:
                            echo "<p>Url was successfully created</p>";
                            break;
                        case 2:
                            echo "<p>You are missing something</p>";
                        case 7:
                            echo "<p>Custom Url is taken</p>";
                        case 12:
                            echo "<p> Please try again later </p>";
                        default;
                            echo "<p>Unknown Error </p>";
                            break;
                }
            
            }
                $_SESSION['err'] = 0;
            ?>
                <input placeholder="URL" name="url" type="text" required>
                <input placeholder="Custom Path (Optional)" name="path" type="text">
                <label><input type="submit" value="submit" class="waves-effect waves-light btn fwid"></label>
            </form>
        </div>
    </div>
    <div class="card clear-top" style="margin-top:80px;">
        <div class="card-action">
            <a class="card-title" href="manage.php">Manage Urls</a>
        </div>
    </div>
</body>
</html>