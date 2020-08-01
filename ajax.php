<?php
include "Database.php";
$sql = "SELECT farbe FROM kategorie WHERE id = :id";
$kommando = $db->getDbCon()->prepare($sql);

$kommando->bindParam(":id", $_GET['color']);
$kommando->execute();
while ($zeile = $kommando->fetch(PDO::FETCH_OBJ)) {

    echo $zeile->farbe;
}
