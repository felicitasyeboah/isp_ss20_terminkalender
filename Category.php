<?php
require_once "EventFactory.php";

/**
 *  Umsetzung der Anforderung "Löschen einer Kategorie".
 * 
 * Zuerst wird das betroffene Objekt mit Hilfe der ID aus der Datenbank erschaffen.
 * Anschließend erfolgt Durchführung der Funktionalität.
 */
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

/**
 * Category
 */
class Category
{
    private $id;
    private $name;
    private $farbe;

    /**
     * Entwurfsmuster Observer
     *
     * @var Termin[] Array aus Termin-Objekten
     */
    private $observers = [];

    public function __construct() {}
    public function __set($name, $value) {}
    public function __get($name) {return $this->$name;}

        
    /**
     * Im Grunde: Kombinierte setter-Funktion
     *
     * @param  string $name
     * @param  string $farbe
     * @return void
     */
    public function addDetails($name, $farbe) {
      $this->name = $name;
      $this->farbe = $farbe;
    }
    
    /**
     * Aktualisieren dieses Kategorie-Datensatzes.
     *
     * @return void
     */
    public function updateCategory() {

        $sql = "UPDATE `kategorie` SET `farbe` = '" . $this->farbe . "', `name` = '". $this->name ."' WHERE `kategorie`.`id` =" . $this->id . "";
        $GLOBALS["db"]->update($sql);
    }
    
    /**
     * Löschen einer Kategorie aus der Datenbank.
     *
     * @return void
     */
    public function deleteCategory() {

        $sql = "DELETE FROM `kategorie` WHERE `kategorie`.`id` =" . $this->id . "";
        $GLOBALS["db"]->update($sql);
    }
        
    /**
     * Hinzufügen einer neuen Kategorie in der Datenbank
     *
     * @param  string $name
     * @param  string $farbe
     * @return void
     */
    public function addCategory($name, $farbe) {
        $sql = "INSERT INTO `kategorie` (`name`, `farbe`) VALUES('" . $name . "','" . $farbe . "')"; //SQL Statement
        $GLOBALS["db"]->insert($sql);
        echo "Neue Category " . $name . " wurde in der Database eingetragen.<br/>";
    }
        
    /**
     * Entwurfsmuster Observer
     * 
     * Ein Termin-Objekt wird zum Observer-Array hinzugefügt.
     *
     * @param  Termin $observer
     * @return void
     */
    public function attach($observer)
    {
        array_push($this->observers, $observer);
    }
       
    /**
     * Entwurfsmuster Observer
     * 
     * Benachrichtigen aller beobachteten Objekten.
     *
     * @return void
     */
    public function notify()
    {
      foreach( $this->observers as $observer ) {
        $observer->update();
      }
    }

  
    //\\\\\\\\\\\\\\\\ GETTER- und SETTER-Methoden \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

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
