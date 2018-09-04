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
    if(isset($_SESSION['err'])){
        switch ($_SESSION['err']) {
            case 0:
                break;
            echo "Permission Error<br>";
                break;
            default;
                echo "Unknown Error <br>";
                break;
    }

}
    $_SESSION['err'] = 0;
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
<nav>
    <span>
        <h5 class="float-left title isplay-inline-block">
            UrSh Admin
        </h5>
    </span>
    <span class="float-right logout-btn"><a href="/admin/logout.php">Logout</a></span>
    <span class="float-none"></span>
</nav>
    <div class="card clear-top">
        <div class="card-content">
            <span class="card-title">Create new Url</span>
            <?php var_dump($_SESSION)?>
        </div>
    </div>
</body>
</html>