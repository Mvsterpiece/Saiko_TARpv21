<?php
$kasutaja = 'saiko_tarpv21'; //d113372_denis
$server = 'localhost'; //d113372.mysql.zonevs.eu
$andmebaas = 'tarpv21'; //d113372_baasdenis
$salasyna='123456';
//teeme käsk mis ühendab andmebaasiga
$yhendus = new mysqli($server,$kasutaja,$salasyna,$andmebaas);
$yhendus -> set_charset('UTF8');
/*
CREATE TABLE loomad(
    id int PRIMARY KEY AUTO_INCREMENT,
    tantsupaar varchar(50),
    punktid int default 0,
    kommentaarid varchar(250),
    avalik int default 1,
    avaliku_paev datetime)
*/

?>
