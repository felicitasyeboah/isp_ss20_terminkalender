<?php
include "dbConf.php";

$dbinst = new PDO("mysql:dbname=" . MYSQL_DB . ";host=" . MYSQL_HOST . ";charset=utf8", MYSQL_BENUTZER, MYSQL_KENNWORT);
$dbinst->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//TODO: Die Tabellen werden auch angelegt, wenn sie schon exisiteren -> Durch
//TODO: mehrfaches LAden der createTable.php werden die defaultkategorien immer der bestehenden tabelle hinzugefügt!

//Tabelle "termin" anlegen, wenn sie noch nicht existiert
try {
    $createTable = "CREATE TABLE IF NOT EXISTS `termine` ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `anfang` DATETIME NOT NULL , 
        `ende` DATETIME NOT NULL , 
        `ganztag` TINYINT(1) NOT NULL DEFAULT '0' , 
        `titel` VARCHAR(65) NOT NULL , 
        `beschreibung` TEXT , 
        `ort` VARCHAR(65) ,
        `farbe` CHAR(7) ,
        `gruppe` CHAR(32) ,
        `kategorieid` INT , 
         PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;";
    if ($dbinst->exec($createTable)) {
        echo "Tabelle Termine angelegt.";
    }
    $sql = "INSERT INTO `termine` (`id`, `anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorieid`) VALUES (NULL, '2020-08-15 07:00:00', '2020-08-15 09:00:00', '0', 'Zweiter Testtermin', 'Das ist der zweite Testtermin, der über die php-funktion eingetragen wurde', 'da wo ich wohne', '1');";
    if ($dbinst->exec($sql)) {
        echo "TestTermin erfolgreich eingetragen.";
    }
} catch (PDOException $e) {
    // Nachricht bei Fehler
    exit("Fehler beim Anlegen der Database-Tabelle!" .
        $e->getMessage());
}

//Tabelle "kategorie" anlegen, wenn sie noch nicht existiert
try {
    $createTable = "CREATE TABLE IF NOT EXISTS `kategorie` ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `name` VARCHAR(65) NOT NULL , 
        `farbe` CHAR(7) , 
         PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_bin;";
    if ($dbinst->exec($createTable)) {
        echo "Tabelle Category angelegt.";
    }
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'Privat', '#9DDF20');";
    $dbinst->exec($sql);
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'Uni', '#5882FA');";
    $dbinst->exec($sql);
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'Arbeit', '#FFBF00');";
    $dbinst->exec($sql);
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'Hobby', '#FF4000');";
    $dbinst->exec($sql);
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'Kat 5', '#FFFF00');";
    $dbinst->exec($sql);
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'Kat 6', '#00FFFF');";
    $dbinst->exec($sql);
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'Kat 7', '#0040FF');";
    $dbinst->exec($sql);
    $sql = "INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES (NULL, 'Kat 8', '#5F04B4');";
    if ($dbinst->exec($sql)) {
        echo "<p> Defaultkategorien erfolgreich eingetragen. </p>
    <input type=\"button\" name=\"home\" value=\"Zum Kalender\" onclick=\"window.location.replace('index.php')\">
";
    }
} catch (PDOException $e) {
    // Nachricht bei Fehler
    exit("Fehler beim anlegen der Database-Tabelle kategorie!" .
        $e->getMessage());
}

?>