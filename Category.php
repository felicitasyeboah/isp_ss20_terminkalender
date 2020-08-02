<?php
require_once "EventFactory.php";

if (isset($_GET["deletecat"])) {
  $id = isset($_GET["id"]) ? intval($_GET["id"]) : null;
  if (isset($id)) {
    $cat = $GLOBALS["db"]->getKategorie($id);
    $factory = new EventFactory();
    $events= $factory->getEventsbyCategory($id);
    foreach ($events as &$event) {
      $cat->attach($event);
      }
  }

  if (isset($cat) && isset($_GET["deletecat"])) {
      $cat->deleteCategory();
      $cat->notify();
      $ausgabe = "Kategorie " . $cat->getName() . " erfolgreich gelöscht!";
      $ausgabe .= '</br> <input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')">';
      echo $ausgabe;
  }
}

class Category
{
    private $id;
    private $name;
    private $farbe;
    private $observers = [];

    public function __construct() {}
    public function __set($name, $value) {}
    public function __get($name) {return $this->$name;}

    public function addDetails($name, $farbe) {
      $this->name = $name;
      $this->farbe = $farbe;
    }

    public function updateCategory() {

        $sql = "UPDATE `kategorie` SET `farbe` = '" . $this->farbe . "', `name` = '". $this->name ."' WHERE `kategorie`.`id` =" . $this->id . "";
        $GLOBALS["db"]->update($sql);
    }

    public function deleteCategory() {

        $sql = "DELETE FROM `kategorie` WHERE `kategorie`.`id` =" . $this->id . "";
        $GLOBALS["db"]->update($sql);
    }
    public function addCategory($name, $farbe) {
        $sql = "INSERT INTO `kategorie` (`name`, `farbe`) VALUES('" . $name . "','" . $farbe . "')"; //SQL Statement
        $GLOBALS["db"]->insert($sql);
        echo "Neue Category wurde in der Database eingetragen.<br/>";
    }
    public function attach($observer)
    {
        array_push($this->observers, $observer);
    }
   
    public function notify()
    {
      foreach( $this->observers as $observer ) {
        $observer->update();
      }
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