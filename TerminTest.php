<?php

include "Datenbank.php";

/**
 * Class TerminTest
 *
 */
class TerminTest
{
    private $anfang;
    private $ende;
    private $ganztaegig;
    private $titel;
    private $beschreibung;
    private $ort;
    private $kategorie;
    private $farbe;
    private $kategorieid;


    public function __construct($titel, $beschreibung, $anfang, $ende, $ort, $ganztaegig = '0', $kategorie = '0', $farbe = '0')
    {
        if(isset($this->kategorieid)) $this->kategorie = $GLOBALS["db"]->getKategorie($this->kategorieid);

        $this->anfang = $anfang;
        $this->ende = $ende;
        $this->titel = $titel;
        $this->beschreibung = $beschreibung;
        $this->ort = $ort;
        $this->gantaegig = $ganztaegig;
        $this->kategorie = $kategorie;
        $this->farbe = $farbe;

    }
    //\\ gibt den Termin in HTML-Ansicht zurück
    public function toHTML(): string {
        $html  = '<div class="event"';
        if(isset($this->kategorie)) $html .= ' style="border-left: Solid 12px #'. $this->farbe . '"';
        $html .= 'id="' . $this->id . '"';
        $html .= 'title="' . $this->beschreibung . '&#013;' . $this->ort . '"';
        $html .= ' onClick="zeigeEvent(' . $this->id . ')" ';
        $html .= '>' . $this->titel . '</div>';

        return $html;
    }
    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
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
     * @return string
     */
    public function getGanztaegig(): string
    {
        return $this->ganztaegig;
    }

    /**
     * @param string $ganztaegig
     */
    public function setGanztaegig(string $ganztaegig)
    {
        $this->ganztaegig = $ganztaegig;
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
     * @return string
     */
    public function getKategorie(): string
    {
        return $this->kategorie;
    }

    /**
     * @param string $kategorie
     */
    public function setKategorie(string $kategorie)
    {
        $this->kategorie = $kategorie;
    }

    /**
     * @return string
     */
    public function getFarbe(): string
    {
        return $this->farbe;
    }

    /**
     * @param string $farbe
     */
    public function setFarbe(string $farbe)
    {
        $this->farbe = $farbe;
    }

    public function printEvent()
    {
     ;
    }

}

?>