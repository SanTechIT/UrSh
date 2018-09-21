<?php
session_start();
if (isset($_SESSION["loggedIn"])){
} else {
    $_SESSION["loggedIn"] = false;
    $_SESSION["name"] = "";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="/js/jquery-3.2.1.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
    <link href="/css/primary.css" rel="stylesheet" type="text/css">
    <link href="/css/loginpage.css" rel="stylesheet" type="text/css">
    <title> Atdp Merch Store </title>
    <script>
        $(document).ready(function(){
            $("a.login").click((event) => {  
                $(".choosebtns").css("height","0px")
                $(".loginbox").css("max-height","1000px")
                console.log("loginclicked")
            });
            $("a.create").click((event) => {  
                $(".choosebtns").css("height","0px")
                $(".choosebtns").css("margin-top","0px")
                $(".createbox").css("max-height","1000px")
                console.log("createclicked")
            });
        })
    </script>
</head>
<body>
    <nav class="light-green darken-3">
    <h4 class="navtitle"><a href="/rchang/p2/">UrSh Admin Login</a></h4>
        <span class="user float-right">
            <?php
                if($_SESSION['loggedIn'] == true){
                    echo '<a href="/admin/index">' . htmlspecialchars($_SESSION["name"]) . '</a>';
                } else {
                }
            ?>
        </span>
    </nav>
    <div class="container">
        <div class="card logincard">
            <div class="card-action center">
                <div class="choosebtns">
    <?php
    if(isset($_SESSION['err'])){
        switch ($_SESSION['err']) {
            case 0:
                break;
            case 1:
                echo "Username or password is wrong<br>";
                break;
            case 2:
                echo "You forgot something<br>";
                break;
            case 9:
                echo "SQL Error<br>";
                break;
            case 10:
                echo "That username already Exsists<br>";
                break;
            case 11:
                echo "Username is too long<br>";
                break;
            default;
                echo "Unknown Error <br>";
                break;
        }
    }
    $_SESSION['err']=0;
?>
                    <a class="login green-text">Log In</a>
                    <a class="create blue-text">Create Account</a>
                </div>
            </div>
            <div class="card-content">
                <div class="loginbox">
                <form class="col s12" action="loginhandler.php" method="POST">
                        <div class="row">
                          <div class="input-field col s12">
                            <input placeholder="Username" name="username" type="text" required>
                          </div>
                          <div class="input-field col s12">
                            <input placeholder="Password" name="password" type="password" required>
                          </div>
                        </div>
                        <input class="btn waves-effect waves-light" type="submit" value="Login" name="action"> </input>
                        </form>
                </div>
                <!-- 
                    Create Login
                -->
                <div class="createbox">
                <div class="row">
                      <form class="col s12" action="createhandler.php" method="POST">
                        <div class="row">
                          <div class="input-field col s6">
                            <input placeholder="First Name" name="first_name" type="text" required>
                          </div>
                          <div class="input-field col s6">
                            <input placeholder="Username" name="username" type="text" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12">
                            <input placeholder="Password" name="password" type="password" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12">
                            <input placeholder="Email" name="email" type="email" required>
                          </div>
                        </div>
                        <input class="btn waves-effect waves-light" type="submit" value="Create Account" name="action"> </input>
                      </form>
                    </div>
                </div>
            </div>
        </card>
    </div>
</body>
</html>