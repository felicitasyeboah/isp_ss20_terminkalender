<?php

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

    public function __construct() {}
    public function __set($name, $value) {}
    public function __get($name) {return $this->$name;}

    //\\ gibt den Termin in HTML-Ansicht zurück
    public function toHTML(): string {
        $html = '<div class="event"';
        $html .= 'id="' . $this->id . '"';
        $html .= 'title="' . $this->beschreibung . '&#013;' . $this->ort . '"';
        $html .= '" onClick="zeigeEvent(' . $this->id . ')" ';
        $html .= '>' . $this->titel . '</div>';

        return $html;
    }
}

?>