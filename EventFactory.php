<?php

require_once "Event.php";
require_once "Datenbank.php";

class EventFactory
{
    public function __construct() {
          
    }

    public function createEvent($titel, $beschreibung, $anfang, $ende, $ort, $kategorieid, $farbe, $ganztag = '0') {

      $event = new Event();

      $event->setAnfang($anfang);
      $event->setEnde($ende);
      $event->setTitel($titel);
      $event->setBeschreibung($beschreibung);
      $event->setOrt($ort);
      $event->setGanztag($ganztag);
      $event->setKategorieid($kategorieid);
      if($event->getKategorieid() !== '') {
          $event->setKategorie($GLOBALS["db"]->getKategorie($event->getKategorieid()));
          if($event->getKategorie()->getFarbe() !== $farbe) {
              $event->setFarbe($farbe);
          }
      }

      return $event;
    }

    public function getEvent($id) {

      $sql = "SELECT * FROM `termine` WHERE `id` = " . $id . "";
      $result = $GLOBALS["db"]->select($sql);
      $event = $this->createEvent($result['titel'], $result['beschreibung'], $result['anfang'], $result['ende'], $result['ort'], $result['kategorieid'], $result['farbe'], $result['ganztag']);

      return $event;
    }

    /** FERTIG
     * Events an einem Tag holen
     */
    public function getEventsonDay($jahr, $monat, $tag)
    {
        $events = [];

        $sql = "SELECT * FROM `termine` WHERE (YEAR(`anfang`) = " . $jahr . " AND MONTH(`anfang`) = " . $monat . " AND DAY(`anfang`) = " . $tag . ") ORDER BY `anfang` ASC";
        $result = $GLOBALS["db"]->select($sql);

        foreach ($result as &$event) {
          $termin = $this->createEvent($event['titel'], $event['beschreibung'], $event['anfang'], $event['ende'], $event['ort'], $event['kategorieid'], $event['farbe'], $event['ganztag']);

          array_push($events, $termin);
      }

      unset($event); // Entferne die Referenz auf das letzte Elemen
        
        //Event Objekte machen

        return $events;

    } // Ende Funktion getEventsonDay()

    public function getEventbyId($id) : Event {

      $event = NULL;

      $select = $this->dbCon->prepare("SELECT *
                                    FROM `termine`
                                    WHERE `id` = :id");

      if ($select->execute([':id' => $id])) {
        $event = $select->fetchObject('Event');
      }

      return ($event) ? $event : NULL;
    }

    public function getEventsforCategory($category)
    {

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