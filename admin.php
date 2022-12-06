<?php
require_once('connect.php');


session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ablogin.php');
    exit();
}


//punktide nulliks
if (isset($_REQUEST['punkt0'])) {
    global $yhendus;
    $kask = $yhendus->prepare('
UPDATE tantsud SET punktid=0 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt0']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}

//peitmine
if (isset($_REQUEST['peitmine'])) {
    global $yhendus;
    $kask = $yhendus->prepare('
UPDATE tantsud SET avalik=0 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['peitmine']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}

//näitamine
if (isset($_REQUEST['naitamine'])) {
    global $yhendus;
    $kask = $yhendus->prepare('
UPDATE tantsud SET avalik=1 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['naitamine']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}

//kustutamine paari
if (isset($_REQUEST["kustutusid"])) {
    global $yhendus;
    $kask = $yhendus->prepare("DELETE FROM tantsud WHERE id=?");
    $kask->bind_param("s", $_REQUEST['kustutusid']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}


//kommentaari kustutamine
if (isset($_REQUEST['komment0'])) {
    global $yhendus;
    $kask = $yhendus->prepare('
UPDATE tantsud SET kommentaarid="" WHERE id=?');
    $kask->bind_param("s", $_REQUEST['komment0']);
    $kask->execute();

    header("Location: $_SERVER[PHP_SELF]");
}

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv21 tantsud</title>
    <link rel="stylesheet" type="text/css" href="stylee.css">
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
            Kustutamine
        </th>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid
        </th>
        <th>
            Kommentaarid / kommentaari kustutamine
        </th>
        <th>
            Avalikumistamise staatus
        </th>
        <th>
            Avaliku päev
        </th>
    </tr>

    <?php
    // tabeli sisu näitamine
    global $yhendus;
    $kask = $yhendus->prepare('
SELECT id, tantsupaar, punktid, kommentaarid, avaliku_paev, avalik FROM tantsud');
    $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $avaliku_paev, $avalik);
    $kask->execute();
    while ($kask->fetch()) {
        echo "<tr>";
        ?>
        <td><a style="color: black" href="?kustutusid=<?=$id ?>"
               onclick="return confirm('Kas ikka soovid kustutada?')">Kustutada</a>
        </td>
        <?php
        $tekst = 'Näita';
        $seisund = 'naitamine';
        $kasutajatekst = 'Kasutaja ei näe';
        if ($avalik == 1) {
            $tekst = 'Peida';
            $seisund = 'peitmine';
            $kasutajatekst = 'Kasutaja näeb';
        }
            echo "<td>" . $tantsupaar . "</td>";
            echo "<td>" . $punktid . "<br><a href='?punkt0=$id' style='color: black'>punktd nulliks</a></td>";
            $kommentaarid = nl2br(htmlspecialchars($kommentaarid));
            echo "<td>" . $kommentaarid . "<br><a href='?komment0=$id' style='color: black'>kustuta kommenti</a></td>";

            echo "<td>$kasutajatekst<br>
            <a href='?$seisund=$id' style='color: black'>$tekst</a><br>
            
            
            </td>";
            echo "<td>" . $avaliku_paev . "</td>";

        echo "</tr>";
    }
    ?>


<style>

table {
    width: 100%;
    border-collapse: collapse;
}
/* Zebra striping */
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



</table>
</body>
</html>