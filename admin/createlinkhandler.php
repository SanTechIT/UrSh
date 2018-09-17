<?php
session_start();
require("config.php");
if (isset($_SESSION['loggedIn'])){
if($_SESSION['loggedIn']){
} else {
    $_SESSION['err'] = 99;
    header("Location: /admin/index.php");
    exit();
}
} else {
    $_SESSION['err'] = 99;
    header("Location: /admin/index.php");
    exit();
}
try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
    echo "<p>Error connecting to database!</p>" . $e;
}
$sth = $dbh->prepare("SELECT Users.User_Active from Users WHERE User_Id=:uid");
$sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
$sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
$sth->execute();
if($user[0]['User_Active'] == 1){
    if(isset($_POST['url']) && isset($_POST['path']) && isset($_SESSION['uid'])){
        $sth = $dbh->prepare("INSERT INTO Urls (Url_Path, Url_Link, Url_Active, Url_User_Id) VALUES (:path, :url, '1', :uid);");
            try {
                $path = '/' . $_POST['path'];
                $sth->bindValue(':url', $_POST['url'], PDO::PARAM_STR);
                $sth->bindValue(':path', $path, PDO::PARAM_INT);
                $sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
                $sth->execute();
                $_SESSION['err'] = 3;
                header("Location: /admin/index.php");
            } 
            catch (PDOException $e){
                $_SESSION['err'] = 9;
                header("Location: /admin/index.php");
        }
    } else {
        $_SESSION['err'] = 2;
        header("Location: /admin/index.php");
    }
} else {
    $_SESSION['err'] = 1;
        header("Location: /admin/logout.php");
}
?>