<?php
include "dbConf.php";

$dbinst = new PDO("mysql:dbname=" . MYSQL_DB . ";host=" . MYSQL_HOST . ";charset=utf8", MYSQL_BENUTZER, MYSQL_KENNWORT);
$dbinst->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Tabelle "termin" anlegen, wenn sie noch nicht existiert
try {
    $createTable = "CREATE TABLE IF NOT EXISTS `termine` ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `anfang` DATETIME NOT NULL , 
        `ende` DATETIME NOT NULL , 
        `ganztag` TINYINT(1) NOT NULL DEFAULT '1' , 
        `titel` VARCHAR(65) NOT NULL , 
        `beschreibung` TEXT NOT NULL , 
        `ort` VARCHAR(65) NOT NULL , 
        `kategorieid` INT NOT NULL , 
         PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;";
    if($dbinst->exec($createTable)) {
        echo "Tabelle angelegt.";
    }
    $sql = "INSERT INTO `termine` (`id`, `anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorieid`) VALUES (NULL, '2020-07-15 07:00:00', '2020-07-15 09:00:00', '0', 'Zweiter Testtermin', 'Das ist der zweite Testtermin, der über die php-funktion eingetragen wurde', 'da wo ich wohne', '1');";
    if($dbinst->exec($sql)) {
        echo "TestTermin erfolgreich eingetragen.";
    }
} catch (PDOException $e) {
    // Nachricht bei Fehler
    exit("Fehler beim anlegen der Datenbank-Tabelle!" .
        $e->getMessage());
}

//Tabelle "kategorie" anlegen, wenn sie noch nicht existiert
try {
    $createTable = "CREATE TABLE IF NOT EXISTS `kategorie` ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `name` VARCHAR(65) NOT NULL , 
        `farbe` CHAR(6) NOT NULL , 
         PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;";
    if($dbinst->exec($createTable)) {
        echo "Tabelle angelegt.";
    }
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'privat', '9DDF20');";
    if($dbinst->exec($sql)) {
        echo "TestTermin erfolgreich eingetragen.";
    }
} catch (PDOException $e) {
    // Nachricht bei Fehler
    exit("Fehler beim anlegen der Datenbank-Tabelle kategorie!" .
        $e->getMessage());
}

?>