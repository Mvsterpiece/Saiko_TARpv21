<?php
require_once('connect.php');
global $yhendus;
session_start();
if (isset($_SESSION['tuvastamine'])) {
    header('Location: admin.php');
    exit();
}
//kontrollime kas väljad on täidetud
if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    //eemaldame kasutaja sisestusest kahtlase pahna
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    //SIIA UUS KONTROLL
    $sool = 'taiestisuvalinetekst';
    $kryp = crypt($pass, $sool);
//    //kontrollime kas andmebaasis on selline kasutaja ja parool
//    $paring = "SELECT * FROM kasutajad WHERE kasutaja='$login' AND parool='$kryp'";
//    $valjund = mysqli_query($yhendus, $paring);
//    //kui on, siis loome sessiooni ja suuname
    $kask=$yhendus->prepare(" SELECT kasutaja,onAdmin, koduleht from kasutajad WHERE kasutaja=? AND parool=?");
    $kask->bind_param("ss",$login,$kryp);
    $kask->bind_result($nimi, $onAdmin, $koduleht);
    $kask->execute();

    if($kask->fetch()){

    //if (mysqli_num_rows($valjund)==1) {
        $_SESSION['tuvastamine'] = 'misiganes';
        $_SESSION['kasutaja'] = $nimi;
        $_SESSION['onAdmin'] = $onAdmin;
        if(isset($koduleht) && $onAdmin==1){
            header('Location: admin.php');
        }
        else{
            header('Location: tantsud.php');
            exit();
        }
    } else {
        echo "kasutaja $login voi parool $kryp on vale";
    }
}

//if (!empty($_POST['login']) && !empty($_POST['pass'] && !empty($_REQUEST['uuekasutaja']))) {
//    global $yhendus;
//    $sool = 'taiestisuvalinetekst';
//    $kryp = crypt($pass, $sool);
//    $kask=$yhendus->prepare("INSERT INTO kasutajad (kasutaja, parool) VALUES(?,?)");
//    $kask->bind_param("ss", $_REQUEST['login'], $kryp);
//    $kask->execute();
//
//    header("Location: $_SERVER[PHP_SELF]");
//
//}


?>
<h1>Login</h1>
<form action="" method="post">
    Login: <input type="text" name="login"><br>
    <br>
    Password: <input type="password" name="pass"><br>
    <br>
    <input type="submit" value="Logi sisse">
    <a href="register.php" style="color: black">Register</a>
</form>


<?php
//CREATE TABLE kasutajad(
//    id int PRIMARY KEY AUTO_INCREMENT,
//    kasutaja varchar(10),
//    parool varchar(250))
?>
<style>
    input[type=text], select {
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        background-color: #000;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }




</style>


