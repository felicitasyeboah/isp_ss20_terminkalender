<?php

require_once "Event.php";
require_once "Database.php";

class EventFactory
{
    public function __construct() {
          
    }

    public function createEvent($tempid,$titel, $beschreibung, $anfang, $ende, $ort, $kategorieid, $farbe, $ganztag = '0') {

      $events = [];
      $gruppe = null;

      //\\ Intervall ausrechnen, um zu prüfen, ob wir mehrere Tage haben
      $date1 = new DateTime($anfang);
      $date2 = new DateTime($ende);
      $interval = $date1->diff($date2);

      //\\ einen eindeutigen Bezeichner für die Gruppe erstellen
      if($interval->days > 0) {
        $gruppe = md5(uniqid());
        echo "!! " . $gruppe . " !!";
      }

      for ($i = 0; $i <= $interval->days; $i++) {
        $event = new Event();
        $event->setAnfang($date1->format('Y-m-d H:i:s'));
        $event->setEnde($date1->format('Y-m-d H:i:s'));
        $event->setTitel($titel);
        $event->setBeschreibung($beschreibung);
        $event->setOrt($ort);
        $event->setGanztag($ganztag);
        if(isset($gruppe)) $event->setGruppe($gruppe);
        $event->setKategorieid($kategorieid);
        if($event->getKategorieid() !== '') {
            $event->setKategorie($GLOBALS["db"]->getKategorie($event->getKategorieid()));
            if($event->getKategorie() !== null) {
              if($event->getKategorie()->getFarbe() !== $farbe) {
                  $event->setFarbe($farbe);
              }
            }
        }
        if($tempid != "") {
            $event->setId($tempid);
        }


        $date1->add(new DateInterval('P1D'));
        array_push($events, $event);
      }

      return $events;
    }

    public function updateEvent() {
        //$sql = "UPDATE buecher SET $spalte = (?) WHERE buecher . id = $id";
        //UPDATE `termine` SET `ort` = 'geaenderter Ort' WHERE `termine`.`id` = 5
        //$sql = "UPDATE `termine` SET `ort` = 'geaenderter Ort' WHERE `termine`.`id` = 5";

    }

    public function getEventbyId($id) {

      $sql = "SELECT * FROM `termine` WHERE `id` = " . $id . "";
      $event = $GLOBALS["db"]->select($sql, "Event")[0];

      //\\ gehört der Termin zu einer Gruppe, dann den ganzen Termin zusammenbauen
      if($event->getGruppe() !== null) {
        $event = $this->getEventGroup($event);
      }

      return $event;
    }

    /**
     * Hilfsfunktion! Holt die Termine einer Gruppe und bastelt daraus ein Termin-Objekt
     */
    private function getEventGroup($event) {

      $gtermin = $event;
      $events = [];

      //\\ Elemente der Gruppe einlesen, in aufsteigender Reihenfolge des Anfangsdatums
      $events = $this->getEventbyGroup($event->getGruppe());

      //\\ wir brauchen das Anfangsdatum des ersten Objekts...
      $gtermin->setAnfang(reset($events)->getAnfang());

      //\\ und das Ende-Datum des letzten Elements
      $gtermin->setEnde(end($events)->getEnde());

      return $gtermin;
    }

    /**
     * Holt alle Termine, die zu einer Gruppe gehören
     */
    public function getEventbyGroup($gruppe) {

      $events = [];

      $sql = "SELECT * FROM `termine` WHERE `gruppe` = '" . $gruppe . "' ORDER BY `anfang` ASC";
      //echo $sql;
      $events = $GLOBALS["db"]->select($sql, "Event");

      return $events;
    }

    /**
     * Events an einem Tag holen
     */
    public function getEventsonDay($jahr, $monat, $tag, $kategorie = null)
    {
        $events = [];

        $sql = "SELECT * FROM `termine` WHERE (YEAR(`anfang`) = " . $jahr . " AND MONTH(`anfang`) = " . $monat . " AND DAY(`anfang`) = " . $tag . ") ORDER BY `anfang` ASC";
        $events = $GLOBALS["db"]->select($sql, "Event");

        if($kategorie !== 0) {
          array_filter($events, function ($element) use ($kategorie) { return ($element != $kategorie); } );
        }
        
        return $events;

    } // Ende Funktion getEventsonDay()

    public function getEventsforCategory($catId)
    { 
      $events = [];

      $sql = "SELECT * FROM `termine` WHERE `kategorieid` = '" . $catId . "";
      $events = $GLOBALS["db"]->select($sql, "Event");

      return $events;

    } // Ende Funktion getEventsforCategory()

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