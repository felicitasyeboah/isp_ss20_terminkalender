<?php

include "Datenbank.php";

/**
 * Class Termin
 *
 */
class Termin
{
    private $start;
    private $ende;
    private $ganztaegig;
    private $titel;
    private $beschreibung;
    private $ort;
    private $kategorie;
    private $farbe;


    public function __construct($start, $ende, $titel, $beschreibung, $ort, $ganztaegig = '0', $kategorie = '0', $farbe = '0')
    {
        $this->start = $start;
        $this->ende = $ende;
        $this->titel = $titel;
        $this->beschreibung = $beschreibung;
        $this->ort = $ort;
        $this->gantaegig = $ganztaegig;
        $this->kategorie = $kategorie;
        $this->farbe = $farbe;

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