<?php

class Kategorie
{
    private $id;
    private $name;
    private $farbe;

    public function __construct() {}
    public function __set($name, $value) {}
    public function __get($name) {return $this->$name;}

    public function addDetails($name, $farbe) {
      $this->name = $name;
      $this->farbe = $farbe;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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