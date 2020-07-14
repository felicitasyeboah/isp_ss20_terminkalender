<?php
include "DB_Conf.php";

//Tabelle anlegen, wenn sie noch nicht existiert
try {
    $createTable = "CREATE TABLE IF NOT EXISTS `kalender` ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `anfang` DATETIME NOT NULL , 
        `ende` DATETIME NOT NULL , 
        `ganztag` TINYINT(1) NOT NULL DEFAULT '1' , 
        `titel` VARCHAR(65) NOT NULL , 
        `beschreibung` TEXT NOT NULL , 
        `ort` VARCHAR(65) NOT NULL , 
        `kategorie` VARCHAR(30) NOT NULL , 
        `farbe` TINYINT(1) NOT NULL DEFAULT '0' ,
         PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;";
    if($db->exec($createTable)) {
        echo "Tabelle angelegt.";
    }
    $sql = "INSERT INTO `kalender` (`id`, `anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorie`, `farbe`) VALUES (NULL, '2020-07-15 07:00:00', '2020-07-15 09:00:00', '0', 'Zweiter Testtermin', 'Das ist der zweite Testtermin, der über die php-funktion eingetragen wurde', 'da wo ich wohne', 'privat', '0');";
    if($db->exec($sql)) {
        echo "TestTermin erfolgreich eingetragen.";
    }
} catch (PDOException $e) {
    // Nachricht bei Fehler
    exit("Fehler beim anlegen der Datenbank-Tabelle!" .
        $e->getMessage());
}
/*
function insertAppointment() {
    $sql = "INSERT INTO `kalender` (`id`, `anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorie`, `farbe`) VALUES (NULL, '2020-07-15 07:00:00', '2020-07-15 09:00:00', '0', 'Zweiter Testtermin', 'Das ist der zweite Testtermin, der über die php-funktion eingetragen wurde', 'da wo ich wohne', 'privat', '0');";
    $db->exec($sql);
}
*/

?>