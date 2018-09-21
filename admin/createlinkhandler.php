<?php
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include ("$root/configs/config.php");

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
/* Credit for geneator to https://stackoverflow.com/questions/4356289/php-random-string-generator I was too lazy */
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



/* Logic here can be cleaned up (moving check for path inside to first request area */
$sth = $dbh->prepare("SELECT Users.User_Active from Users WHERE User_Id=:uid");
$sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
$sth->execute();
$user = $sth->fetchAll();
if($user[0]['User_Active'] == 1){
    if(isset($_POST['url']) && (strlen($_POST['path'])!=0) && isset($_SESSION['uid'])){
        /* */

        $sth = $dbh->prepare("SELECT * from Urls where Url_Path = :urlpath");
        $path = '/' . $_POST['path'];
        $sth->bindValue(':urlpath', $path ,PDO::PARAM_STR);
        $sth->execute();
        $urllist = $sth->fetchAll();
        if(count($urllist) != 0){
            $_SESSION['err'] = 7;
            header("Location: /admin/index.php");
            exit();
        }

        $sth = $dbh->prepare("INSERT INTO Urls (Url_Path, Url_Link, Url_Active, Url_User_Id) VALUES (:path, :url, '1', :uid);");
            try {
                $path = '/' . $_POST['path'];
                $sth->bindValue(':url', $_POST['url'], PDO::PARAM_STR);
                $sth->bindValue(':path', $path, PDO::PARAM_INT);
                $sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
                $sth->execute();
                $_SESSION['err'] = 3;
                header("Location: /admin/index.php");
                exit();
            } 
            catch (PDOException $e){
                $_SESSION['err'] = 9;
                header("Location: /admin/index.php");
                exit();
        }
    } else {
        $_SESSION['err'] = 2;
    } 
    /* If path isnt given, Generate one istead */
    if(isset($_POST['url']) && isset($_SESSION['uid'])){
        $sth = $dbh->prepare("INSERT INTO Urls (Url_Path, Url_Link, Url_Active, Url_User_Id) VALUES (:path, :url, '1', :uid);");
                $isNotUnique = true;
                $count = 0;
                while ($isUnique){
                    /* If too many failures, generate string with higher count */
                    if($count > 5){
                        $_SESSION['err'] = 12;
                        header("Location: /admin/index.php");
                    } 
                    $path = '/' . generateRandomString(generateLinkLength);
                    $sth = $dbh->prepare("SELECT * from Urls where Url_Path = :path;");
                    $sth->bindValue(':path', $path,PDO::PARAM_INT);
                    $sth->execute();
                    $urllist = $sth->fetchAll();
                    /* Check url Unique? */
                    if(count($urllist) == 0){
                        $isNotUnique = false;
                    }
                    $count++;
                }
            try {
                $path = '/' . generateRandomString(5);
                $sth->bindValue(':url', $_POST['url'], PDO::PARAM_STR);
                $sth->bindValue(':path', $path, PDO::PARAM_INT);
                $sth->bindValue(':uid', $_SESSION['uid'],PDO::PARAM_INT);
                $sth->execute();
                $_SESSION['err'] = 3;
                $_SESSION['meta'] = $path;
                header("Location: /admin/index.php");
            } 
            catch (PDOException $e){
                $_SESSION['err'] = 9;
                header("Location: /admin/index.php");
                exit();
        }
    } else {
        $_SESSION['err'] = 2;
        header("Location: /admin/index.php");
        exit();
    }
} else {
    $_SESSION['err'] = 1;
        header("Location: /admin/logout.php");
        exit();
}
header("Location: /admin/index.php");
?>