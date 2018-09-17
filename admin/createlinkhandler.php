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
    /* Check if user's requested Url already exsists */
        if(isset($_POST['path'])){
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
    }

    if(isset($_POST['url'])  && isset($_SESSION['uid'])){
        $sth = $dbh->prepare("INSERT INTO Urls (Url_Path, Url_Link, Url_Active, Url_User_Id) VALUES (:path, :url, '1', :uid);");
            try {
                if(isset($_POST['path'])){
                $path = '/' . $_POST['path'];
                } else {
                    $isNotUnique = true;
                    $count = 0;
                    while ($isUnique){
                        /* If too many failures, generate string with higher count */
                        if($count > 5){
                            $path = '/' . generateRandomString(6);
                            $sth = $dbh->prepare("SELECT * from Urls where Url_Path = :path;");
                            $sth->bindValue(':path', $path,PDO::PARAM_INT);
                            $sth->execute();
                            $urllist = $sth->fetchAll();
                            if($count > 10){
                                $_SESSION['err'] = 12;
                                header("Location: /admin/index.php");
                            }
                        } 
                        $path = '/' . generateRandomString(5);
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
                }
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