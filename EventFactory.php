<?php

require_once "Event.php";
require_once "Database.php";

/**
 * EventFactory
 * 
 * Entwurfsmuster Factory
 * 
 * Stellt verschiedene Funktionen bereit, welche Event-Objekte erstellen.
 */
class EventFactory
{
  public function __construct()
  {
  }

  /**
   * Erstellt ein oder mehrere neue Event-Objekte, welche nicht aus der Datenbank kommt.
   * 
   * Findet Anwendung beim Erstellen neuer Events oder Bearbeiten vorhandener Events.
   * ACHTUNG: Liefert immer ein Array zurück! (Events über mehrere Tage werden als Einzelevents gespeichert!)
   *
   * @param  mixed $tempid
   * @param  mixed $titel
   * @param  mixed $beschreibung
   * @param  mixed $anfang
   * @param  mixed $ende
   * @param  mixed $ort
   * @param  mixed $kategorieid
   * @param  mixed $farbe
   * @param  mixed $ganztag
   * @return Event[]
   */
  public function createEvent($tempid, $titel, $beschreibung, $anfang, $ende, $ort, $kategorieid, $farbe, $ganztag = '0')
  {

    $events = [];
    $gruppe = null;

    //\\ Intervall ausrechnen, um zu prüfen, ob das Event über mehrere Tage geht
    $date1 = new DateTime($anfang);
    $date2 = new DateTime($ende);
    $interval = $date1->diff($date2);

    //\\ Ein Event wird in der Datenbank über mehrere Einzelevents realisiert
    //\\ Die Zusammenführung erfolgt über eine Gruppen-Id, welche hier erzeugt wird.
    if ($interval->days > 0) {
      $gruppe = md5(uniqid());
    }

    //\\ Nun für jeden Tag des Intervalls ein Event anlegen
    for ($i = 0; $i <= $interval->days; $i++) {
      $event = new Event();
      $event->setAnfang($date1->format('Y-m-d H:i:s'));
      $event->setEnde($date2->format('Y-m-d H:i:s'));
      $event->setTitel($titel);
      $event->setBeschreibung($beschreibung);
      $event->setOrt($ort);
      $event->setGanztag($ganztag);
      if (isset($gruppe)) $event->setGruppe($gruppe);
      $event->setKategorieid($kategorieid);
      if ($event->getKategorieid() !== '') {
        $event->setKategorie($GLOBALS["db"]->getKategorie($event->getKategorieid()));
        if ($event->getKategorie() !== null) {
          if ($event->getKategorie()->getFarbe() !== $farbe) {
            $event->setFarbe($farbe);
          }
        }
      }
      //\\ wenn ich ein Event bearbeite, gibt es schon eine ID -> mitnehmen
      if ($tempid != "") {
        $event->setId($tempid);
      }

      //\\ Nächsten Tag einstellen...
      $date1->add(new DateInterval('P1D'));

      //\\ ...und Event-Objekt in das Array schreiben
      array_push($events, $event);
    }

    return $events;
  }

    
  /**
   * Erstellt ein neues Event-Objekt, welches per ID aus der Datenbank gelesen wird.
   * 
   * Es wird hier nur das erste Element des Arrays genommen. Sofern dieses Mitgleid einer Gruppe ist,
   * wird mittels einer Hilfsfunktion das komplettes Event erzeugt (auch nur eines).
   *
   * @param  int $id
   * @return Event
   */
  public function getEventbyId($id)
  {

    $sql = "SELECT * FROM `termine` WHERE `id` = " . $id . "";
    $event = $GLOBALS["db"]->select($sql, "Event")[0];

    //\\ gehört das Event zu einer Gruppe, dann den ganzen Termin zusammenbauen
    if ($event->getGruppe() !== null) {
      $event = $this->getEventGroup($event);
    }

    return $event;
  }

   
  /**
   * Holt die Events einer Gruppe anhand der Gruppen-Id aus der Datenbank und bastelt daraus ein einziges Event-Objekt.
   * 
   * Hilfsfunktion für getEventbyID!
   *
   * @param  Event $event
   * @return Event
   */
  private function getEventGroup($event)
  {

    $gtermin = $event;
    $events = [];

    //\\ Elemente der Gruppe einlesen, in aufsteigender Reihenfolge des Anfangsdatums
    $events = $this->getEventbyGroup($event->getGruppe());

    //\\ Wir brauchen das Anfangsdatum des ersten Objekts...
    $gtermin->setAnfang(reset($events)->getAnfang());

    //\\ ... und das Ende-Datum des letzten Elements.
    $gtermin->setEnde(end($events)->getEnde());

    return $gtermin;
  }

  
  /**
   * Holt alle Events, die zu einer Gruppe gehören.
   *
   * @param  string $gruppe
   * @return Event[]
   */
  public function getEventbyGroup($gruppe)
  {

    $events = [];

    $sql = "SELECT * FROM `termine` WHERE `gruppe` = '" . $gruppe . "' ORDER BY `anfang` ASC";
    $events = $GLOBALS["db"]->select($sql, "Event");

    return $events;
  }

    
  /**
   * Erstellt Event-Objekte, welches per Datumsangaben aus der Datenbank geholt werden und filtert diese ggf. nach einer Kategorie.
   * 
   * ACHTUNG: Es wird auf jeden Fall ein Array zurückgeliefert, auch wenn es nur ein Event gibt!
   * 
   * Zusätzlich erfolgt eine Filterung auf eine übergebene Kategorie-ID.
   *
   * @param  mixed $jahr
   * @param  mixed $monat
   * @param  mixed $tag
   * @param  int $kategorie
   * @return EVENT[]
   */
  public function getEventsonDay($jahr, $monat, $tag, $kategorie = null)
  {
    $events = [];

    $sql = "SELECT * FROM `termine` WHERE (YEAR(`anfang`) = " . $jahr . " AND MONTH(`anfang`) = " . $monat . " AND DAY(`anfang`) = " . $tag . ") ORDER BY `anfang` ASC";
    $events = $GLOBALS["db"]->select($sql, "Event");

    //\\ Sofern gewünscht, werden nur die Termine der übergebenen Kategorie im Array belassen
    if ($kategorie !== null) {
      $events = array_filter($events, function ($event) use ($kategorie) {
        return ($event->getKategorieid() === $kategorie);
      });
    }
    
    return $events;
  }
  
  /**
   * Erstellt Event-Objekte, welche zur übergebenen Kategorie-ID aus der Datenbank geholt werden.
   * 
   * ACHTUNG: Es wird auf jeden Fall ein Array zurückgeliefert, auch wenn es nur ein Event gibt!
   *
   * @param  int $catId
   * @return Event[]
   */
  public function getEventsbyCategory($catId)
  {
    $events = [];

    $sql = "SELECT * FROM `termine` WHERE `kategorieid` = " . $catId . "";
    $events = $GLOBALS["db"]->select($sql, "Event");

    return $events;
  }
}
