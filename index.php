<?php
/*
    Establish DB connection
*/

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require ("$root/configs/config.php");
try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
    echo "<p>Error connecting to database!</p>" . $e;
}

/*
    Get Url and parse 
    (Fowarded by Apache/IIS)
*/

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
/* https://stackoverflow.com/questions/6768793/get-the-full-url-in-php - ax. */
$values = parse_url($url);
$url= explode('.',$values['path'])[0];

/*
    Harcoded Url Fowarding Overides (Incase user tries to f around)
*/

switch ($url) {
    case "/admin/":
        header("Location: /admin/index.php");
        break;
    case "/favicon":
        exit();
        break;
    case "/":
        header("Location: /admin/index.php");
        exit();
        break;
    case "/GooGle":
        header("Location: https://google.com");
        break;
    default:
        break;
}

/*
    Check DB if short link exsists
*/

$sth = $dbh->prepare("SELECT * FROM Urls JOIN Users ON Urls.Url_User_Id=Users.User_Id where Url_Path=:url AND Url_Active='1' AND User_Active = '1'");
$sth->bindValue(':url', $url, PDO::PARAM_STR);
$sth->execute();
$dburl = $sth->fetchAll();

$ip = $_SERVER['REMOTE_ADDR'];

$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
var_dump($details);

/*
Stores the IP of the visitor, time of visit, and (if set) the Http Referer
SQL REQUEST GOES HERE
*/

/*
echo '<br>' . $_SERVER['REMOTE_ADDR'];
echo '<br>' . date("m/d/Y");
echo '<br>' . date("h:i:s");
echo '<br>' . $_SERVER['HTTP_REFERER'];
*/
/*
if(Debug){
    exit();
    echo '<br> Debug Enabled!';
    $sth = $dbh->prepare("INSERT INTO Visits (Visit_Ip,Visit_Url_Id,Visit_Date,Visit_Time,Visit_Exact_Path,Visit_Refer) VALUES (:bip,'1',:date,:time,:epath,:refer)");
    $sth->bindValue(':bip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $sth->bindValue(':date', date("m/d/Y"), PDO::PARAM_STR);
    $sth->bindValue(':time', date("h:i:s"), PDO::PARAM_STR);
    $sth->bindValue(':epath', $url, PDO::PARAM_STR);
    $sth->bindValue(':refer',$_SERVER['HTTP_REFERER'], PDO::PARAM_STR);
    $sth->execute();
    
}   
else {
*/
if (isset($details->bogon)){
    /*
    IP is "Bogon" so GeoIP data is not avalible.
    */
    echo '<br> Bogon Ip!';
    $sth = $dbh->prepare("INSERT INTO Visits (Visit_Ip,Visit_Url_Id,Visit_Date,Visit_Time,Visit_Exact_Path,Visit_Refer) VALUES (:bip,:urlid,:date,:time,:epath,:refer)");
    $sth->bindValue(':bip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $sth->bindValue(':date', date("m/d/Y"), PDO::PARAM_STR);
    $sth->bindValue(':time', date("h:i:s"), PDO::PARAM_STR);
    $sth->bindValue(':urlid', $dburl[0]['Url_Id'],PDO::PARAM_STR);
    $sth->bindValue(':epath', $url, PDO::PARAM_STR);
    $sth->bindValue(':refer',$_SERVER['HTTP_REFERER'], PDO::PARAM_STR);
    $sth->execute();

} else {
    /*
    IP is not "Bogon" and therefore visitor Geo-IP location is stored in the DB.
    */

    /*
    Visits not being recorded? Check if your server is connected to the internet or if the GeoIp service is down ipinfo.io
    */
    //echo '<br> Regular Ip';
    $sth = $dbh->prepare("INSERT INTO Visits (Visit_Ip,Visit_Url_Id,Visit_Date,Visit_Time,Visit_Exact_Path,Visit_Refer) VALUES (:ip,:urlid,:date,:time,:epath,:refer)");
    $sth->bindValue(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $sth->bindValue(':date', date("m/d/Y"), PDO::PARAM_STR);
    $sth->bindValue(':time', date("h:i:s"), PDO::PARAM_STR);
    $sth->bindValue(':urlid', $dburl[0]['Url_Id'],PDO::PARAM_STR);
    $sth->bindValue(':epath', $url, PDO::PARAM_STR);
    $sth->bindValue(':refer',$_SERVER['HTTP_REFERER'], PDO::PARAM_STR);
    $sth->execute();
}
/*
}
*/
if (count($dburl) != 0){
    /* Add thing to record user info*/
    header("Location: " . $dburl[0]['Url_Link']);
}

/*
    Errors? Check here!
*/

if(isset($_SESSION['err'])){
    switch ($_SESSION['err']) {
        case 0:
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
    <style>
    .err-msg{
        margin-left:20px;
    }
    </style>
</head>
<body>
<nav>
<p class="err-msg">
<?php
echo "The url \"" . $url . "\" could not be found! ";
echo "<br> Remeber that these urls are case sensitive!";

/* https://stackoverflow.com/questions/5257243/php-how-to-split-url */

/*
Core Functions

1. Get url DONE
2. Parse Url and get path DONE
3. Check if path exsists in database DONE

  - If exists, record visitor database DONE
  - Check if URL is "Active" and if Owner of URL is "Active" DONE
  - Send user to url stored in database DONE

  - If does not exsist, return error page DONE

Admin Functions

1. Login DONE
2. Normal User WIP
    A. Add DONE / Manage WIP / Delete Urls TBS
    B. See Stats on owned Urls WIP
3. Admin User TBS
    A. Inherit Normal User
    B. Manage and freeze users
 */
?>

</p>
</nav>
</body>
</html>