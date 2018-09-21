<?php
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include ("$root/configs/config.php");
                try {
                    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
                } catch (PDOException $e) {
                    echo "<p>Error connecting to database!</p>" . $e;
                }
                if (isset($_POST['username'])){
                    $sth = $dbh->prepare("SELECT * FROM Users WHERE username =:username");
                    $sth->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
                    $sth->execute();
                    $usr = $sth->fetchAll();
                } else {
                    $_SESSION['err'] = 2;
                    header("Location: /admin/login");
                }
                if(count($usr) == 0){
                    if(isset($_POST['first_name']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])){
                        $sth = $dbh->prepare("INSERT INTO Users (`User_First_Name`, `Username`,`Password_Hash`,`User_Email`,`User_Active`,`User_Admin`) VALUES (:first_name, :username, :password, :email,'1','0')");
                            try {
                                $sth->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
                                $sth->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
                                $sth->bindValue(':password', password_hash($_POST['password'],PASSWORD_DEFAULT));
                                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                                    $sth->bindValue(':email', $_POST['email']);
                                } else{
                                    echo "Invalid Email <br>";
                                    return;
                                }
                                $sth->execute();
                                $_SESSION['err'] = 0;
                                header("Location: /admin/login");
                            } 
                            catch (PDOException $e){
                                $_SESSION['err'] = 9;
                                header("Location: /admin/login");
                        }
                    } else {
                        $_SESSION['err'] = 2;
                        header("Location: /admin/login");
                    }
                } else {
                    $_SESSION['err'] = 10;
                    header("Location: /admin/login");
                } 
            ?>
