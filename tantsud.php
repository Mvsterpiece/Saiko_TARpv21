<?php
require_once ('connect.php');

session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ablogin.php');
    exit();
}


global $yhendus;
$sorttulp = "tantsupaar";
$otsisona = "";


if (isset($_REQUEST["$sorttulp"])) {
    $sorttulp = $_REQUEST["sorttulp"];
}
if (isset($_REQUEST["otsisona"])) {
    $otsisona = $_REQUEST["otsisona"];
}


//Otsimine ja sorteerimine
function kysiPaarideAndmed($sorttulp = "tantsupaar", $otsisona = "")
{
    global $yhendus;

    $lubatudtulbad=array("tantsupaar", "punktid","kommentaarid");
    if(!in_array($sorttulp, $lubatudtulbad))
    {
        return "lubamatu tulp";
    }
        if($sorttulp=="punktid")
    {
        $sorttulp="Punktid";
    }
        if($sorttulp=="kommentaarid")
    {
        $sorttulp="kommentaarid";
    }
    $otsisona = addslashes(stripslashes($otsisona));
    $kask = $yhendus->prepare("Select id, tantsupaar, punktid, kommentaarid, pilt from tantsud
    WHERE avalik=1
        AND (tantsupaar LIKE '%$otsisona%' OR punktid LIKE '%$otsisona%' OR kommentaarid LIKE '%$otsisona%')
       ORDER BY $sorttulp");
    $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $pilt);
    $kask->execute();
    $hoidla = array();
    while ($kask->fetch()) {
        $Tpaar = new stdClass();
        $Tpaar->id = $id;
        $Tpaar->tantsupaar = htmlspecialchars($tantsupaar);
        $Tpaar->punktid = $punktid;
        $Tpaar->kommentaarid = htmlspecialchars($kommentaarid);
        $Tpaar->pilt=htmlspecialchars($pilt);
        array_push($hoidla, $Tpaar);
    }
    return $hoidla;
}
$paarid = kysiPaarideAndmed($sorttulp, $otsisona);

function isAdmin(){
    return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'];
}

//Uue tantsupaari lisamine
if (!empty($_REQUEST['paarinimi']) && isAdmin()){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO tantsud (tantsupaar, avaliku_paev, pilt) VALUES(?,NOW(),?)");
    $kask->bind_param("ss", $_REQUEST['paarinimi'], $_REQUEST['pilt']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");

}
//kommentaaride lisamine
if(isset($_REQUEST['uuskomment'])) {
    if (!empty($_REQUEST['komment'])) {
        global $yhendus;
        $kask = $yhendus->prepare("UPDATE tantsud SET kommentaarid=CONCAT(kommentaarid,?) WHERE id=?");
        $kommentplus=$_REQUEST['komment']."\n";
        $kask->bind_param("si",$kommentplus,$_REQUEST['uuskomment']);
        $kask->execute();


        header("Location: $_SERVER[PHP_SELF]");
    }
}

//punktide lisamine
if(isSet($_REQUEST['punkt'])){
    global $yhendus;
    $kask=$yhendus->prepare('
UPDATE tantsud SET punktid=punktid+1 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}



?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv21 tantsud</title>
</head>
<body>
<header>
    <h1>Tantsud TARpv21</h1>
    <nav>
        <ul>
            <li>
                <a href="tantsud.php">Kasutaja leht</a>
            </li>
            <li>
                <a href="admin.php">Admin leht</a>
            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <ul>
            <li>

            </li>
        </ul>
        <div>
            <?php echo $_SESSION['kasutaja']." sisse logitud" ?>
            <form action="logout.php" method="post">
                <input type="submit" value="Logi välja" name="logout">
            </form>
        </div>
    </nav>
</header>
<table>
    <tr>
        <th>
            <form action="?">
                <input type="text" name="otsisona" placeholder="Otsi....">
            </form>
        </th>
    </tr>
    <tr>
        <th><a href="?sorttulp=tantsupaar" style="color: white">Tantsupaar</th>
        <th><a href="?sorttulp=punktid" style="color: white">Punktid</th>
        <th>Haldus</th>
        <th><a href="?sorttulp=kommentaarid" style="color: white">Kommentaarid</th>
        <th>Kommentaari lisamine</th>
        <th>Pilt</th>
    </tr>

    <?php
    //tabeli sisu näitamine
    $kask = $yhendus->prepare('
    SELECT id, tantsupaar, punktid, kommentaarid, pilt FROM tantsud WHERE avalik=1');
    $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $pilt);
    $kask->execute();

    foreach ($paarid as $tantsupaar):

        echo "<tr>";
        echo "<th>" . $tantsupaar->tantsupaar . "</th>";
        echo "<th>" . $tantsupaar->punktid . "</th>";
        echo "<th>" . "<a href='?punkt=$tantsupaar->id' style='color: white'>Lisa 1 punkt</a>" . "</th>";
        echo "<th>" . nl2br($tantsupaar->kommentaarid) . "</th>";
        echo "<th> 
            <form action='?'>
            <input type='hidden' value='$tantsupaar->id' name='uuskomment'>
            <input type='text' name='komment'>
            <input type='submit' value='OK'>
            </form>
            </th>";

        echo "<th> <img width='150'  src='$tantsupaar->pilt' alt='pilt'></th>";
        echo "</tr>";

        ?>
    <?php endforeach; ?>
</table>

<div class="name">
    <h2>Tantsupaari lisamine</h2>
    <form action="?">
        <input type="text" placeholder="TantsupaariNimed" name="paarinimi">
        <br>
        <textarea name='pilt'>Siia lisa pildi aadress.</textarea><br>
        <input type="submit" value="OK">
    </form>

</div>

<style>

table {
    width: 100%;
    border-collapse: collapse;
}
/* Zebra striping */
tr:nth-of-type(odd) {
    background: #eee;
}
th {
    background: #333;
    color: white;
    font-weight: bold;
}
td, th {
    padding: 6px;
    border: 1px solid #ccc;
    text-align: left;
}
body {
    height: 125vh;
    background-size: cover;
    font-family: sans-serif;
    margin-top: 80px;
    padding: 30px;
}

main {
    color: white;
}

header {
    background-color: white;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 80px;
    display: flex;
    align-items: center;
    box-shadow: 0 0 25px 0 black;
}

header * {
    display: inline;
}

header li {
    margin: 20px;
}

header li a {
    color: black;
    text-decoration: none;
}




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
</body>