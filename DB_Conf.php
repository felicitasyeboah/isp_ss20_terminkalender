<?php

// Alle Fehler anzeigen lassen
error_reporting(E_ALL);

// Zugangsdaten zur Datenbank als Konstanten
define("MYSQL_HOST", "localhost");
define("MYSQL_BENUTZER", "root");
define("MYSQL_KENNWORT", "");
define("MYSQL_DB", "termin_kal");


/**
 * Baut eine Verbindung zur MYSQL Datenbank auf und gibt diese Verbindung zurück
 * @return mysqli
 */
function linkDB()
{
    $db_con = new mysqli(MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT, MYSQL_DB);
    if ($db_con->connect_errno) {
        die("Verbindung fehlgeschlagen. Fehler: " . $db_con->connect_error);
    }
    echo "Verbindungsaufbau erfolgreich.";
    return $db_con;
//$db_con->close();
}
/*
// Verbindung zur Datenbank aufbauen
try {
    //"mysql:(MYSQL_DB);host=(MYSQL_HOST)"
    $db = new PDO("mysql:dbname=" . MYSQL_DB . ";host=" . MYSQL_HOST . ";charset=utf8", MYSQL_BENUTZER, MYSQL_KENNWORT);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Verbindung ueber PDO hergestellt.";
} catch (PDOException $e) {
    //echo "Fehler: " . htmlspecialchars($e->getMessage());
    exit("Fehler beim Verbindungsaufbau: " . htmlspecialchars($e->getMessage()));
}
*/


?>