<?php

//require_once("Database.php");
require_once("EventFactory.php");
//include_once("EventFactory.php");

if (isset($_GET["details"])) {
  $id = isset($_GET["id"]) ? intval($_GET["id"]) : null;
  if(isset($id)) {
    $factory = new EventFactory();
    $termin = $factory->getEvent($id)[0];
  }

  if(isset($termin)) {
    echo $termin->toHTMLDetails();
  }
}

class Event
{
    private $id;
    private $titel;
    private $anfang;
    private $ende;
    private $anfangsdatum;
    private $anfangszeit;
    private $enddatum;
    private $endzeit;
    private $ganztag;
    private $beschreibung;
    private $ort;
    private $kategorieid;
    private $kategorie; //\\ Objekt der Klasse "Category"
    private $farbe;

//\\ neuen Konstruktor zum Erstellen eines Termins verwenden
public function __construct() {
        //\\ holen des Category-Datensatzes für diesen Event
        if(isset($this->kategorieid)) {
            $this->kategorie = $GLOBALS["db"]->getKategorie($this->kategorieid);
        }
      }

      public function addKategorie($name, $farbe) {
        $this->kategorie = new Category();
        $this->kategorie->addDetails($name, $farbe);
      }


      public function addEvent()
      {
          try {
            if($this->kategorie !== null) {
              if($this->kategorie->getId() === null) {
                if($this->kategorie->getName() !== '') {            
                  //$sql = "SELECT * FROM `termine` WHERE (YEAR(`anfang`) = " . $jahr . " AND MONTH(`anfang`) = " . $monat . " AND DAY(`anfang`) = " . $tag . ") ORDER BY `anfang` ASC";
                  if($this->getId() == "") {
                      $sql = "INSERT INTO `kategorie` (`name`, `farbe`) VALUES('" . $this->kategorie->getName() . "','" . $this->kategorie->getFarbe() . "')"; //SQL Statement
                      $GLOBALS["db"]->insert($sql);
                      echo "Neue Category wurde in der Database eingetragen.<br/>";
                  } else {
                      //$kommando = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
                      //$kommando->execute(array($termin->getKategorie()->getName(), $termin->getKategorie()->getFarbe()));
                      $this->kategorieid = $GLOBALS["db"]->getlastId();
                  }
                }
              }
            }
            else {
              $this->kategorieid = null;
            }
             //gibt es einen Termin noch nicht, dann eintragen
                if ($this->getId() == "") {
                    $sql = "INSERT INTO `termine` (`anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorieid`) VALUES ('" . $this->anfang . "','" . $this->ende . "'," . $this->ganztag . ",'" . $this->titel . "','" . $this->beschreibung . "','" . $this->ort . "',"; //SQL Statement
                    if ($this->kategorieid === null) {
                        $sql .= "NULL)";
                    } else {
                        $sql .= $this->kategorieid . ")";
                    }
                    //$kommando = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
                    //$kommando->execute(array($termin->getAnfang(), $termin->getEnde(), $termin->getGanztag(), $termin->getTitel(), $termin->getBeschreibung(), $termin->getOrt(), $termin->getKategorieid()));
                    $GLOBALS["db"]->insert($sql);
                    echo "Event wurde in der Database eingetragen.<br/>";
                    //sonst Termin bereits vorhanden, dann Updaten
                } else {
                    $sql = "UPDATE `termine` SET `anfang` = '" . $this->anfang . "', `ende` = '$this->ende', `ganztag` = '. $this->ganztag .', `titel` = '" . $this->titel . "', `beschreibung` = '" . $this->beschreibung . "', `ort` = '". $this->ort ."' WHERE `termine`.`id` =".$this->id ."";
                    $GLOBALS["db"]->update($sql);
                }
              //$this->dbCon = null; // Verbindung zur DB wird geschlossen
          } catch (Exception $e) { // Wenn ein Fehler beim Eintragen des Titels in die DB auftritt, wird er im Catchblock
              // gecatched und der Fehler ausgegeben.
              echo "Fehler: " . $e->getMessage();
          }
      }


    public function __set($name, $value) {}
    //TODO hier wird nen Fehler angezeigt  beim hinzufuegen von termien deshalb auskommentiert
    //public function __get($name) {return $this->$name;}

    //\\ gibt den Event in HTML-Ansicht zurück
    public function toHTML(): string {
        $html  = '<div class="event"';
        if(isset($this->kategorie) && !isset($this->farbe)) $html .= ' style="border-top: Solid 6px '. $this->kategorie->farbe . '"';
        if(isset($this->farbe)) $html .= ' style="border-top: Solid 12px '. $this->farbe . '"';
        $html .= 'id="' . $this->id . '"';
        $html .= 'title="' . $this->beschreibung . '&#013;' . $this->ort . '"';
        $html .= ' onClick="zeigeEvent(' . $this->id . ')" ';
        $html .= '>' . $this->titel . '</div>';

        return $html;
    }

    public function toHTMLDetails() : string {
      //\\ Kopfzeile / Navigation
      $html  = '<tr><td colspan="10">';
      $html .= '<table id="eventNav"';
      if(isset($this->kategorie)) $html .= ' style="background-color: ' . $this->kategorie->farbe . '"'; 
      $html .= '"><tbody><tr>';
      $html .= '<th>';
      $html .= '<span class="eventTitle">' . $this->titel . '</span>';
      $html .= '</th>';
      $html .= '<td>';
      $html .= '<div class="eventLink ico_edit" onclick="window.location.replace(\'editEvent.php\?id='. $this->id .'\')"></div>';
      $html .= '</td>';
      $html .= '<td>';
      $html .= '<div class="eventLink ico_del" onclick="window.location.replace(\'Event.php\?delete&id='. $this->id .'\')"></div>';
      $html .= '</td>';
      $html .= '</tr>';
      $html .= '</tbody></table>';

      //\\ EventDetails
      $html .= '<div id="eventDetails">';
      $html .= $this->anfang . ' - ' . $this->ende . '</br>';
      $html .= $this->beschreibung . '</br>';
      $html .= $this->ort . '</br>';
      if(isset($this->kategorie)) $html .= $this->kategorie->name . '</br>';

      $html .= '</div>';

      //\\ Abschluss
      $html .= '</td>';

      return $html;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * @param mixed $titel
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;
    }

    /**
     * @return mixed
     */
    public function getAnfang()
    {
        return $this->anfang;
    }

    /**
     * @param mixed $anfang
     */
    public function setAnfang($anfang)
    {
        $this->anfang = $anfang;
    }

    /**
     * @return mixed
     */
    public function getAnfangsdatum()
    {
        $tmpstr = substr($this->anfang, 0,10);
        return $tmpstr;
    }

    /**
     * @return mixed
     */
    public function getAnfangszeit()
    {
        $tmpstr = substr($this->anfang, 11,8);
        return $tmpstr;
    }

    /**
     * @return mixed
     */
    public function getEnde()
    {
        return $this->ende;
    }

    /**
     * @param mixed $ende
     */
    public function setEnde($ende)
    {
        $this->ende = $ende;
    }

    /**
     * @return mixed
     */
    public function getEnddatum()
    {
        $tmpstr = substr($this->ende, 0,10);
        return $tmpstr;
    }

    /**
     * @return mixed
     */
    public function getEndzeit()
    {
        $tmpstr = substr($this->anfang, 11,8);
        return $tmpstr;
    }

    /**
     * @return mixed
     */
    public function getGanztag()
    {
        return $this->ganztag;
    }

    /**
     * @param mixed $ganztag
     */
    public function setGanztag($ganztag)
    {
        $this->ganztag = $ganztag;
    }

    /**
     * @return mixed
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * @param mixed $beschreibung
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
    }

    /**
     * @return mixed
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * @param mixed $ort
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;
    }

    /**
     * @return mixed
     */
    public function getKategorieid()
    {
        return $this->kategorieid;
    }

    /**
     * @param mixed $kategorieid
     */
    public function setKategorieid($kategorieid)
    {
        $this->kategorieid = $kategorieid;
    }

    /**
     * @return mixed
     */
    public function getKategorie()
    {
        return $this->kategorie;
    }

    /**
     * @param mixed $kategorie
     */
    public function setKategorie($kategorie)
    {
        $this->kategorie = $kategorie;
    }

    /**
     * @return mixed
     */
    public function getFarbe()
    {
        return $this->farbe;
    }

    /**
     * @param mixed $farbe
     */
    public function setFarbe($farbe)
    {
        $this->farbe = $farbe;
    }

}

?>