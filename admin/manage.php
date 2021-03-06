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
        header("Location: /admin/login");
        exit();
    }
} else {
    $_SESSION["loggedIn"] = false;
    header("Location: /admin/login");
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
            <a href="index">
            UrSh Admin
            </a>
        </h5>
    </span>
    <span class="float-right logout-btn"><a href="/admin/logout">Logout</a></span>
    <span class="float-none"></span>
</nav>
<div class="card clear-top" style="margin-top:80px;">
        <div class="card-content">
            <span class="card-title">Manage Urls</span>

            <form method="POST" action="createlinkhandler">
            <?php
                if(isset($_SESSION['err'])){
                    switch ($_SESSION['err']) {
                        case 0:
                            break;
                        case 5:
                            echo "<p>You are missing something</p>";
                        default;
                            echo "<p>Unknown Error </p>";
                            break;
                }
            
            }
                $_SESSION['err'] = 0;
                $sth = $dbh->prepare("SELECT * from Urls WHERE Url_User_Id=:uid");
                $sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
                $sth->execute();
                $urls = $sth->fetchAll();
                /*
                $sth = $dbh->prepare("select Visits.* from Urls join Visits ON Url_Id = Visits.Visit_Url_Id join Users ON Url_User_Id = Users.User_Id WHERE User_Id = :uid");
                $sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
                $sth->execute();
                $urlvisits = $sth->fetchAll();
                */
                echo '<br> <br>This will be more readable in the future I promise I will try<br>';
                echo '<table>';
                echo '<tr>';
                echo '<td> Url Path </td>';
                echo '<td> Url Link </td>';
                echo '<td> Url Clicks (TBA) </td>';
                echo '<td> Manage Url </td>';
                echo '</tr>';
                foreach($urls as $url){
                    echo '<tr>';
                    echo '<td>' . $url['Url_Path'] . '</td>';
                    echo '<td>' . $url['Url_Link'] . '</td>';
                    echo '<td>' . 'Count' . '</td>';
                    echo '<td>' . '<a href="manageurl?url=' . $url['Url_Path'] . '">Manage</a>' . '</td>';
                    echo '<tr>';
                }
            ?>
            </form>
        </div>
    </div>
</body>
</html>