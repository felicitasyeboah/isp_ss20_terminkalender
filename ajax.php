<?php
include "Database.php";
/*
 * Holt die Farbe einer Kategorie aus der Datenbank
 */
$sql = "SELECT farbe FROM kategorie WHERE id = :id";
$kommando = $db->getDbCon()->prepare($sql);

$kommando->bindParam(":id", $_GET['color']);
$kommando->execute();
while ($zeile = $kommando->fetch(PDO::FETCH_OBJ)) {
    echo $zeile->farbe;
}
