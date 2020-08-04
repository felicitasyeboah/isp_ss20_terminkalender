<?php

require_once("EventFactory.php");

/**
 *  Umsetzung der Anforderungen "Ansicht der Details" und "Löschen eines Termins".
 * 
 * In beiden Fällen wird das betroffene Objekt mit Hilfe der ID aus der Datenbank erschaffen.
 * Anschließend erfolgt die Unterscheidung und entsprechende Durchführung der Funktionalität.
 */
if (isset($_GET["details"]) || isset($_GET["delete"])) {
    $id = isset($_GET["id"]) ? intval($_GET["id"]) : null;
    if (isset($id)) {
        $factory = new EventFactory();
        $termin = $factory->getEventbyId($id);
    } else {
        echo "Keine ID gesetzt!";
    }

    if (isset($termin) && isset($_GET["details"])) {
        echo $termin->toHTMLDetails();
    }

    if (isset($termin) && isset($_GET["delete"])) {
        $termin->deleteEvent();
        $ausgabe = "Termin " . $termin->getTitel() . " erfolgreich gelöscht!";
        $ausgabe .= '</br> <input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')">';
        echo $ausgabe;
    }
}

/**
 * Class Event
 */
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
    private $gruppe;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        //\\ Die Kategorie zum Termin wird, als Objekt hinzugefügt.
        if (isset($this->kategorieid)) {
            $this->kategorie = $GLOBALS["db"]->getKategorie($this->kategorieid);
        }
    }


    /**
     * Setzen der Eigenschaften eines neu erstellten Kategorie-Objekts.
     *
     * @param  string $name
     * @param  string $farbe
     * @return void
     */
    public function addKategorie($name, $farbe)
    {
        $this->kategorie = new Category();
        $this->kategorie->addDetails($name, $farbe);
    }



    /**
     * Fügt dieses Objekt in die Datenbank ein.
     *
     * @return void
     */
    public function addEvent()
    {
        try {
            $sql = "INSERT INTO `termine` (`anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorieid`, `gruppe`) VALUES ('" . $this->anfang . "','" . $this->ende . "'," . $this->ganztag . ",'" . $this->titel . "','" . $this->beschreibung . "','" . $this->ort . "',"; //SQL Statement
            if ($this->kategorieid == '') {
                $sql .= "NULL";
            } else {
                $sql .= $this->kategorieid;
            }
            if ($this->gruppe === null) {
                $sql .= ",NULL)";
            } else {
                $sql .= ",'" . $this->gruppe . "')";
            }

            $GLOBALS["db"]->insert($sql);
            echo "Event wurde in der Database eingetragen.<br/>";
        } catch (Exception $e) { // Wenn ein Fehler beim Eintragen des Titels in die DB auftritt, wird er im Catchblock
            // gecatched und der Fehler ausgegeben.
            echo "Fehler beim Hinzufügen des Events: " . $e->getMessage();
        }
    }


    /**
     * Löscht dieses Event aus der Datenbank.
     * 
     * Sofern der Termin zu einer Gruppe von Terminen gehört, wird die komplette Gruppe gelöscht.
     *
     * @return void
     */
    public function deleteEvent()
    {
        if ($this->isGroupEvent()) {
            $sql = "DELETE FROM `termine` WHERE `gruppe` = '" . $this->gruppe . "'";
        } else {
            $sql = "DELETE FROM `termine` WHERE `id` = " . $this->id . "";
        }
        $GLOBALS["db"]->delete($sql);
    }


    /**
     * Ändet die Daten dieses Objekts in der Datenbank.
     *
     * @return void
     */
    public function updateEvent()
    {
        $tmpKat = "";
        $tmpGru = "";
        if ($this->kategorieid == '') {
            $tmpKat = "NULL";
        } else {
            $tmpKat = $this->kategorieid;
        }
        if ($this->gruppe === null) {
            $tmpGru = "NULL";
        } else {
            $tmpGru .= "'" . $this->gruppe . "'";
        }
        $sql = "UPDATE `termine` SET `anfang` = '" . $this->anfang . "', `ende` = ' $this->ende ', `ganztag` = $this->ganztag , `titel` = '" . $this->titel . "'
        , `beschreibung` = '" . $this->beschreibung . "', `ort` = '" . $this->ort . "' , `kategorieid` = " . $tmpKat . " , `gruppe` = " . $tmpGru . " WHERE `termine`.`id` =" . $this->id . "";

        $GLOBALS["db"]->update($sql);
    }

    /**
     * Entwurfsmuster Observer
     * 
     * Diese Funktion wird aufgerufen (benachrichtigt), wenn das Objekt im Observer-Array vorhanden ist
     *
     * @return void
     */
    public function update()
    {
        $this->kategorieid = null;
        $this->updateEvent();
    }


    /*public function __set($name, $value)
    {
    }*/

    /**
     * Gibt das Event in HTML-Ansicht zurück.
     * 
     * @return string
     */
    public function toHTML()
    {
        $html = '<div class="event"';
        if (isset($this->kategorie)) $html .= ' style="border-top: Solid 6px ' . $this->kategorie->farbe . '"';
        $html .= 'id="' . $this->id . '"';
        $html .= 'title="' . $this->beschreibung . '&#013;' . $this->ort . '"';
        $html .= ' onClick="zeigeEvent(' . $this->id . ')" ';
        $html .= '>' . $this->titel . '</div>';

        return $html;
    }

    /**
     * Gibt die Eventdetails in HTML-Ansicht zurück.
     * 
     * @return string
     */
    public function toHTMLDetails()
    {
        //\\ Kopfzeile / Navigation
        $html = '<tr><td colspan="10">';
        $html .= '<table id="eventNav"';
        if (isset($this->kategorie)) $html .= ' style="background-color: ' . $this->kategorie->farbe . '"';
        $html .= '"><tbody><tr>';
        $html .= '<th>';
        $html .= '<span class="eventTitle">' . $this->titel . '</span>';
        $html .= '</th>';
        $html .= '<td>';
        $html .= '<div class="eventLink ico_edit" onclick="window.location.replace(\'editEvent.php\?id=' . $this->id . '\')"></div>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<div class="eventLink ico_del" onclick="window.location.replace(\'Event.php\?delete&id=' . $this->id . '\')"></div>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</tbody></table>';

        //\\ EventDetails
        $html .= '<div id="eventDetails">';
        $html .= $this->anfang . ' - ' . $this->ende . '</br>';
        $html .= $this->beschreibung . '</br>';
        $html .= $this->ort . '</br>';
        if (isset($this->kategorie)) $html .= $this->kategorie->name . '</br>';

        $html .= '</div>';

        //\\ Abschluss
        $html .= '</td>';

        return $html;
    }

        
    /**
     * Gehört das Event zu einer Gruppe?
     *
     * @return bool
     */
    public function isGroupEvent()
    {
        return ($this->gruppe === null) ? false : true;
    }


    //\\\\\\\\\\\\\\\\ GETTER- und SETTER-Methoden \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @return int
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
        $tmpstr = substr($this->anfang, 0, 10);
        return $tmpstr;
    }

    /**
     * @return mixed
     */
    public function getAnfangszeit()
    {
        $tmpstr = substr($this->anfang, 11, 8);
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
        $tmpstr = substr($this->ende, 0, 10);
        return $tmpstr;
    }

    /**
     * @return mixed
     */
    public function getEndzeit()
    {
        $tmpstr = substr($this->ende, 11, 8);
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
     * @return Kategorie
     */
    public function getKategorie()
    {
        return $this->kategorie;
    }

    /**
     * @param Kategorie $kategorie
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

    /**
     * @return mixed
     */
    public function getGruppe()
    {
        return $this->gruppe;
    }

    /**
     * @param mixed
     */
    public function setGruppe($gruppe)
    {
        $this->gruppe = $gruppe;
    }
}
