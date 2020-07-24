<?php

class Kategorie
{
    private $id;
    private $name;
    private $farbe;

    public function __construct() {}
    public function __set($name, $value) {}
    public function __get($name) {return $this->$name;}
}

?>