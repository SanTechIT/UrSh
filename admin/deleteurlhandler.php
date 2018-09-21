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
/* Check if url is owned by person trying to delete the url */
$sth = $dbh->prepare("SELECT Urls.* from Urls join Users on Url_User_Id=Users.User_Id WHERE Url_Id=:urlid AND User_Id = :uid;");
$sth->bindValue(':urlid', $_GET['id'],PDO::PARAM_INT);
$sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
$sth->execute();
$check = $sth->fetchAll();
if(count($check) != 0){
    if(strlen($_GET['id']) != 0){
            try {
                $sth = $dbh->prepare("DELETE FROM Urls where Url_Id = :urlid;");
                $sth->bindValue(':urlid', $_GET['id'], PDO::PARAM_STR);
                $sth->execute();
            } 
            catch (PDOException $e){
                $_SESSION['err'] = 9;
                header("Location: /admin/index");
                exit();
        }
    } else {
        $_SESSION['err'] = 2;
        header("Location: /admin/manage");
        exit();
    } 
} else {
    $_SESSION['err'] = 3;
        header("Location: /admin/logout");
        exit();
}
header("Location: /admin/manage");

?>