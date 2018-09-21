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

    $sth = $dbh->prepare("Select Visits.* from Urls join Visits ON Url_Id = Visits.Visit_Url_Id join Users ON Url_User_Id = Users.User_Id WHERE Users.User_Id = :uid AND Url_Path = :urlp");
    $sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
    $sth->bindValue(':urlp', $_GET['url'],PDO::PARAM_STR);
    $sth->execute();
    $urlvisits = $sth->fetchAll();

    $sth = $dbh->prepare("Select Urls.* from Urls join Users ON Url_User_Id = Users.User_Id WHERE Users.User_Id = :uid AND Url_Path = :urlp");
    $sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
    $sth->bindValue(':urlp', $_GET['url'],PDO::PARAM_STR);
    $sth->execute();
    $urlinfo = $sth->fetchAll();
    if(count($urlinfo) == 0){
        header("Location: /admin/manage");
    }
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
    <script>
        $(document).ready(function() {
            $(".deleteurl").click((event) => {
                $(".confirm").toggleClass("hidden");
            });
            $(".deleteurlno").click((event) => {
                $(".confirm").toggleClass("hidden");
            });
        });
    </script>
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
<nav class="light-green darken-3" href="index" href="index">
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
<div class="card">
    <div class="card-content" style="margin-top:80px;">
    <span class="card-title">Url Data</span>
        <?php
        echo '<p> Total Clicks: ' . count($urlvisits) . '</p>';
        ?>
    </div>
    <div class="card-action">
        <a class="deleteurl">Delete</a>
    </div>
</div>
<div class="card hidden confirm" style="width:90%; position:absolute; z-index:100; margin-left:5%;">
    <div class="card-content" style="margin-top:80px;">
    <span class="card-title">Are you sure you want to delete this Url?</span>
    </div>
    <div class="card-action">
        <a class="deleteurlyes" href="deleteurlhandler.php?id=<?php echo $urlinfo[0]['Url_Id']; ?>">Yes</a>
        <a class="deleteurlno">Cancel</a>
    </div>
</div>
    <div class="card clear-top" style="margin-top:30px;">
        <div class="card-content">
            <span class="card-title">Click Data</span>

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
        
                // var_dump($urlvisits);
                echo '<table>';
                echo '<tr>';
                echo '<td> Visit Date </td>';
                echo '<td> Visit Time </td>';
                echo '<td> Ip </td>';
                echo '</tr>';
                foreach($urlvisits as $url){
                    echo '<tr>';
                    echo '<td>' . $url['Visit_Date'] . '</td>';
                    echo '<td>' . $url['Visit_Time'] . '</td>';
                    echo '<td>' . $url['Visit_Ip'] . '</td>';
                    echo '<tr>';
                }
            ?>
            </form>
        </div>
    </div>
</body>
</html>