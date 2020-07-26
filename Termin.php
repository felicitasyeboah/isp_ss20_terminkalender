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
    private $farbe;

/*    public function __construct() {
        //TODO auslagern in eine Funktion und Konstruktor benutzen um einen Termin zu erzeugen
        //\\ holen des Kategorie-Datensatzes für diesen Termin
        if(isset($this->kategorieid)) {
            $this->kategorie = $GLOBALS["db"]->getKategorie($this->kategorieid);
            $this->farbe = $GLOBALS["db"]->getKategorie($this->farbe);
        }
    }*/

    public function __construct($titel, $beschreibung, $anfang, $ende, $ort, $kategorieid, $farbe, $ganztag = '0') {

        $this->anfang = $anfang;
        $this->ende = $ende;
        $this->titel = $titel;
        $this->beschreibung = $beschreibung;
        $this->ort = $ort;
        $this->ganztag = $ganztag;
        $this->farbe = $farbe;
        $this->kategorieid = $kategorieid;
        if(isset($this->kategorieid)) {
            $this->kategorie = $GLOBALS["db"]->getKategorie($this->kategorieid);
            $this->farbe = $farbe;

            ///$this->farbe = $GLOBALS["db"]->getKategorie($this->farbe);

            //$this->farbe = $farbe;
        }
        echo "Termin erstellt";
    }
    public function __set($name, $value) {}
    //TODO hier wird nen Fehler angezeigt  beim hinzufuegen von termien deshalb auskommentiert
    //public function __get($name) {return $this->$name;}

/*    public function terminErstellen($titel, $beschreibung, $anfang, $ende, $ort, $kategorieid, $farbe, $ganztag = '0') {

        $this->anfang = $anfang;
        $this->ende = $ende;
        $this->titel = $titel;
        $this->beschreibung = $beschreibung;
        $this->ort = $ort;
        $this->ganztag = $ganztag;
        $this->farbe = $farbe;
        $this->kategorieid = $kategorieid;
        if(isset($this->kategorieid)) {
            $this->kategorie = $GLOBALS["db"]->getKategorie($this->kategorieid);
            $this->farbe = $farbe;
        }
        echo "Termin erstellt";
    }*/
    //\\ gibt den Termin in HTML-Ansicht zurück
    public function toHTML(): string {
        $html  = '<div class="event"';
        if(isset($this->kategorie)) $html .= ' style="border-left: Solid 12px '. $this->kategorie->farbe . '"';
        $html .= 'id="' . $this->id . '"';
        $html .= 'title="' . $this->beschreibung . '&#013;' . $this->ort . '"';
        $html .= ' onClick="zeigeEvent(' . $this->id . ')" ';
        $html .= '>' . $this->titel . '</div>';

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