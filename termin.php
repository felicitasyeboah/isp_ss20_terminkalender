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

    public function __construct()
    {

    }

    public function __set($name, $value) {}
    public function __get($name) {return $this->$name;}
}

?>