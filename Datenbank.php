<?php

require_once "Termin.php";
require_once "Kategorie.php";
require_once "dbConf.php";
//Globale Varable für ein Datenbankobjekt.
$db = new Datenbank(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);

class Datenbank
{
    private $dbCon;

    public function __construct($db, $host, $user, $pass)
    {
        // Verbindung zur Datenbank aufbauen
        try {
            $this->dbCon = new PDO("mysql:dbname=" . $db . ";host=" . $host . ";charset=utf8", $user, $pass);
            $this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Verbindung ueber PDO hergestellt.";
        } catch (PDOException $e) {
            exit("Fehler beim Verbindungsaufbau: " . htmlspecialchars($e->getMessage()));
        }
    }
    public function getDbCon()
    {
        return $this->dbCon;
    }

    /**
     * Traegt einen Temrin in den Kalender ein
     * @param Termin $termin
     */
    public function addEvent(Termin $termin)
    {
        try {
            //$db_con = self::linkDB(); // die mit der function linkDB() aufgebaute Verbindung zur DB wird in $db_con gespeichtert
            $sql = "INSERT INTO `termine` (`anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorieid`) VALUES (?, ?, ?, ?, ?, ?, ?)"; //SQL Statement
            $kommando = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
            $kommando->execute(array($termin->getAnfang(), $termin->getEnde(), $termin->getGanztag(), $termin->getTitel(), $termin->getBeschreibung(), $termin->getOrt(), $termin->getKategorieid()));

            if (($termin->getKategorieid() <= 8) && ($termin->getKategorieid() > 0)) {
                $sql = "UPDATE `kategorie` SET `farbe` = ? WHERE `id` =  ?"; //SQL Statement
                $kommando2 = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
                $kommando2->execute(array($termin->getFarbe(), $termin->getKategorieid()));
            }
            else {
                $sql = "INSERT INTO `kategorie` (`name`, `farbe`) VALUES(?,?)"; //SQL Statement
                $kommando3 = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
                $kommando3->execute(array($termin->getKategorieid(), $termin->getFarbe()));
                //$termin->setKategorie($termin->getKategorie());
            }
            echo "Termin wurde in der Datenbank eingetragen.<br/>";
            $this->dbCon = null; // Verbindung zur DB wird geschlossen
        } catch (Exception $e) { // Wenn ein Fehler beim Eintragen des Titels in die DB auftritt, wird er im Catchblock
            // gecatched und der Fehler ausgegeben.
            echo "Fehler: " . $e->getMessage();
        }
    }

    public function addEvent2(Termin $termin)
    {
        try {
          if($termin->getKategorie() !== null) {
            if($termin->getKategorie()->id === null) {
              if($termin->getKategorie()->getName() !== '') {            
                $sql = "INSERT INTO `kategorie` (`name`, `farbe`) VALUES(?,?)"; //SQL Statement
                $kommando = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
                $kommando->execute(array($termin->getKategorie()->getName(), $termin->getKategorie()->getFarbe()));
                $termin->setKategorieid($this->dbCon->lastInsertId());
                echo "Neue Kategorie wurde in der Datenbank eingetragen.<br/>";
              }
            }
          }
          else {
            $termin->setKategorieid(null);
          }
           

            $sql = "INSERT INTO `termine` (`anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorieid`) VALUES (?, ?, ?, ?, ?, ?, ?)"; //SQL Statement
            $kommando = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
            $kommando->execute(array($termin->getAnfang(), $termin->getEnde(), $termin->getGanztag(), $termin->getTitel(), $termin->getBeschreibung(), $termin->getOrt(), $termin->getKategorieid()));
            echo "Termin wurde in der Datenbank eingetragen.<br/>";
            $this->dbCon = null; // Verbindung zur DB wird geschlossen
        } catch (Exception $e) { // Wenn ein Fehler beim Eintragen des Titels in die DB auftritt, wird er im Catchblock
            // gecatched und der Fehler ausgegeben.
            echo "Fehler: " . $e->getMessage();
        }
    }

    public function getAllEvents()
    {
        //\\ Implementierung wenn benötigt!
    }

    public function getEventsonDay($jahr, $monat, $tag)
    {
        $events = [];

        /*$select = $this->dbCon->prepare("SELECT *
                                      FROM `termine` LEFT JOIN `kategorie` ON termine.kategorieid = kategorie.id
                                      WHERE (YEAR(`anfang`) = :jahr AND MONTH(`anfang`) = :monat AND DAY(`anfang`) = :tag)
                                      ORDER BY `anfang` ASC");*/
        $select = $this->dbCon->prepare("SELECT *
                                      FROM `termine`
                                      WHERE (YEAR(`anfang`) = :jahr AND MONTH(`anfang`) = :monat AND DAY(`anfang`) = :tag)
                                      ORDER BY `anfang` ASC");

        if ($select->execute([':jahr' => $jahr, ':monat' => $monat, ':tag' => $tag])) {
            $events = $select->fetchAll(PDO::FETCH_CLASS, 'Termin');
            //$events = $select->fetchAll();
        }

        return $events;

    } // Ende Funktion getEventsonDay()

    public function getEventbyId($id) : Termin {

      $event = NULL;

      $select = $this->dbCon->prepare("SELECT *
                                    FROM `termine`
                                    WHERE `id` = :id");

      if ($select->execute([':id' => $id])) {
        $event = $select->fetchObject('Termin');
      }

      return ($event) ? $event : NULL;
    }

    public function getKategorie($katid)
    {
        $kategorie = NULL;

        $select = $this->dbCon->prepare("SELECT *
                                    FROM `kategorie`
                                    WHERE `id` = :katid");

        if ($select->execute([':katid' => $katid])) {
            $kategorie = $select->fetchObject('Kategorie');
        }

        return ($kategorie) ? $kategorie : NULL;

    } // Ende Funktion getKategorie()

    public function getEventsforCategory($category)
    {

    } // Ende Funktion getEventsforCategory()

    /**
     * Lädt alle Kategorien ins Dropdownmenue der addEvent.php
     */
    public function getAllKategorien() {

        $sql = "SELECT * FROM `kategorie`";
        $ergebnis = $this->dbCon->query($sql);
        foreach($ergebnis as $zeile) {
            echo "<option value=" . htmlspecialchars($zeile["id"]). ">" . htmlspecialchars($zeile["name"]) . "</option>";

        }
    }

    /*public function getColor($kategorieid){
      $sql = "SELECT farbe FROM kategorie WHERE id = :id";
      $kommando = $this->dbCon->prepare($sql);

      $kommando->bindParam(":id", $kategorieid);
      $kommando->execute();
      while($zeile = $kommando->fetch(PDO::FETCH_OBJ)) {
          $ergebnis = $zeile->farbe;
      }
      return $ergebnis;
    }*/
}

?>