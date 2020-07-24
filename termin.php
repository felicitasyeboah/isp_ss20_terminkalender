<?php

//include "kategorie.php";

class Termin
{
    private $id;
    private $titel;
    private $anfang;
    private $ende;
    private $ganztag;
    private $beschreibung;
    private $ort;
    private $kategorieid;
    private $kategorie;

    public function __construct() {
        //\\ holen des Kategorie-Datensatzes für diesen Termin
        if(isset($this->kategorieid)) $this->kategorie = $GLOBALS["db"]->getKategorie($this->kategorieid);
    }
    public function __set($name, $value) {}
    public function __get($name) {return $this->$name;}

    //\\ gibt den Termin in HTML-Ansicht zurück
    public function toHTML(): string {
        $html  = '<div class="event"';
        if(isset($this->kategorie)) $html .= ' style="border-left: Solid 12px #'. $this->kategorie->farbe . '"';
        $html .= 'id="' . $this->id . '"';
        $html .= 'title="' . $this->beschreibung . '&#013;' . $this->ort . '"';
        $html .= ' onClick="zeigeEvent(' . $this->id . ')" ';
        $html .= '>' . $this->titel . '</div>';

        return $html;
    }
}

?>