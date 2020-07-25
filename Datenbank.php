<?php

include "Termin.php";
include "kategorie.php";

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
    public function getDbCon() {
      return $this->dbCon;
    }

    /**
     * Traegt einen Temrin in den Kalender ein
     * @param Termin $termin
     */
    public function addEvent(Termin $termin) {
        //TODO Bsher werden nur vorgegeben Kategorien eingetragen
        try {
            //$db_con = self::linkDB(); // die mit der function linkDB() aufgebaute Verbindung zur DB wird in $db_con gespeichtert
            $sql = "INSERT INTO `termine` (`anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorieid`) VALUES (?, ?, ?, ?, ?, ?, ?)"; //SQL Statement
            $kommando = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
            $kommando->execute(array($termin->getAnfang(), $termin->getEnde(), $termin->getGanztag(), $termin->getTitel(), $termin->getBeschreibung(), $termin->getOrt(), $termin->getKategorieid()));

            if($termin->getKategorieid() <=8) {
                $sql = "UPDATE `kategorie` SET `farbe` = ? WHERE `id` =  ?"; //SQL Statement
                $kommando2 = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
                $kommando2->execute(array($termin->getFarbe(), $termin->getKategorieid()));
            }
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

      $select = $this->dbCon->prepare("SELECT `id`, `anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `kategorieid`, `ort`
                                      FROM `termine`
                                      WHERE (YEAR(`anfang`) = :jahr AND MONTH(`anfang`) = :monat AND DAY(`anfang`) = :tag)
                                      ORDER BY `anfang` ASC");

      if ($select->execute([':jahr' => $jahr, ':monat' => $monat, ':tag' => $tag])) {
        $events = $select->fetchAll(PDO::FETCH_CLASS, 'Termin');
        //$events = $select->fetchAll();
      }

      return $events;

    } // Ende Funktion getEventsonDay()

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
}

?>