<?php
include "Datenbank.php";
include "dbConf.php";

$dbtmp = new Datenbank(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);

$sql = "SELECT farbe FROM kategorie WHERE id = :id";
$kommando = $dbtmp->getDbCon()->prepare($sql);

$kommando->bindParam(":id", $_GET['color']);
$kommando->execute();
while ($zeile = $kommando->fetch(PDO::FETCH_OBJ)) {

    echo $zeile->farbe;
}
