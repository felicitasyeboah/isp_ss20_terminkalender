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

    public function updateEvent()
    {
        //$sql = "UPDATE buecher SET $spalte = (?) WHERE buecher . id = $id";
        //UPDATE `termine` SET `ort` = 'geaenderter Ort' WHERE `termine`.`id` = 5
        //$sql = "UPDATE `termine` SET `ort` = 'geaenderter Ort' WHERE `termine`.`id` = 5";

    }

    public function getEvent($id)
    {

      $sql = "SELECT * FROM `termine` WHERE `id` = " . $id . "";
      $event = $GLOBALS["db"]->selectObj($sql, "Event");
      //$result = $GLOBALS["db"]->select($sql);
      //$event = $this->createEvent($result['titel'], $result['beschreibung'], $result['anfang'], $result['ende'], $result['ort'], $result['kategorieid'], $result['farbe'], $result['ganztag']);

      return $event;
    }

    /** FERTIG
     * Events an einem Tag holen
     */
    public function getEventsonDay($jahr, $monat, $tag, $kategorie = null)
    {
        $events = [];

        $sql = "SELECT * FROM `termine` WHERE (YEAR(`anfang`) = " . $jahr . " AND MONTH(`anfang`) = " . $monat . " AND DAY(`anfang`) = " . $tag . ") ORDER BY `anfang` ASC";
        $events = $GLOBALS["db"]->selectObj($sql, "Event");

        if($kategorie !== 0) {
          array_filter($events, function ($element) use ($kategorie) { return ($element != $kategorie); } );
        }
        
        return $events;

    } // Ende Funktion getEventsonDay()

    /*public function getEventbyId($id) : Event {

      $event = NULL;

      $select = $this->dbCon->prepare("SELECT *
                                    FROM `termine`
                                    WHERE `id` = :id");

      if ($select->execute([':id' => $id])) {
        $event = $select->fetchObject('Event');
      }

      return ($event) ? $event : NULL;
    }*/

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