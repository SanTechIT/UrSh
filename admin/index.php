<?php
require("config.php");
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
    <title>Index</title>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/materialize.min.js"></script>
    <link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
    <link href="/css/primary.css" rel="stylesheet" type="text/css">
</head>
<body>
<nav>
<p>
Welcome to the admin page
</p>
</nav>
</body>
</html>