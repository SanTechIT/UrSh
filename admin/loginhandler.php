<?php
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include ("$root/configs/config.php");

    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo "<p>Error connecting to database!</p>" . $e;
    } 
    if(isset($_POST['password']) && isset($_POST['username'])){
        $sth = $dbh->prepare("SELECT * FROM Users WHERE username=:username LIMIT 1");
        $sth->bindValue(':username', $_POST['username']);
        $sth->execute();
        $user = $sth->fetchAll();
    if($_POST['username'] == $user[0]['Username']){
        if(password_verify($_POST['password'], $user[0]['Password_Hash'])){
            $_SESSION["loggedIn"] = true;
            $_SESSION["name"] = $user[0]['User_First_Name'];
            $_SESSION["uid"] = $user[0]['User_Id'];
            $_SESSION['isAdmin'] = $user[0]['User_Admin'];
            header("Location: /admin/index.php");
        } else {
            $_SESSION['err'] = 1;
            header("Location: /admin/login.php");
        }
    } else {
        $_SESSION['err'] = 1;
        header("Location: /admin/login.php");
    }
}
else {
    $_SESSION['err'] = 2;
    header("Location: /admin/login.php");
}
?>