<?php

require_once("Database.php");
require_once("Category.php");

if (isset($_GET["details"])) {
  $id = isset($_GET["id"]) ? intval($_GET["id"]) : null;
  if(isset($id)) {
    $termin = $GLOBALS["db"]->getEventbyId($id);
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
              if($this->kategorie->id === null) {
                if($this->kategorie->name !== '') {            
                  //$sql = "SELECT * FROM `termine` WHERE (YEAR(`anfang`) = " . $jahr . " AND MONTH(`anfang`) = " . $monat . " AND DAY(`anfang`) = " . $tag . ") ORDER BY `anfang` ASC";
                  $sql = "INSERT INTO `kategorie` (`name`, `farbe`) VALUES('" . $this->kategorie->name . "','" . $this->kategorie->farbe . "')"; //SQL Statement
                  $GLOBALS["db"]->insert($sql);
                  //$kommando = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
                  //$kommando->execute(array($termin->getKategorie()->getName(), $termin->getKategorie()->getFarbe()));
                  $this->kategorieid = $GLOBALS["db"]->getlastId();
                  echo "Neue Category wurde in der Database eingetragen.<br/>";
                }
              }
            }
            else {
              $this->kategorieid = null;
            }
             
  
              $sql = "INSERT INTO `termine` (`anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorieid`) VALUES ('" . $this->anfang . "','" . $this->ende ."',". $this->ganztag .",'" . $this->titel . "','" . $this->beschreibung ."','" . $this->ort ."'," . $this->kategorieid . ")"; //SQL Statement
              //$kommando = $this->dbCon->prepare($sql); //SQL Statement wird vorbereitet
              //$kommando->execute(array($termin->getAnfang(), $termin->getEnde(), $termin->getGanztag(), $termin->getTitel(), $termin->getBeschreibung(), $termin->getOrt(), $termin->getKategorieid()));
              $GLOBALS["db"]->insert($sql);
              echo "Event wurde in der Database eingetragen.<br/>";
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
      $html .= '<img class="eventLink ico_edit" onclick="window.location.replace(\'editEvent.php\?id='. $this->id .'\')"></img>';
      $html .= '</td>';
      $html .= '<td>';
      $html .= '<img class="eventLink ico_del" onclick="window.location.replace(\'Event.php\?delete&id='. $this->id .'\')"></img>';
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