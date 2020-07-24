<?php

include "termin.php";
include "kategorie.php";

class Datenbank
{
  private $db;

  public function __construct($db, $host, $user, $pass)
  {
    // Verbindung zur Datenbank aufbauen
    try {
      $this->db = new PDO("mysql:dbname=" . $db . ";host=" . $host . ";charset=utf8", $user, $pass);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //echo "Verbindung ueber PDO hergestellt.";
      } catch (PDOException $e) {
        exit("Fehler beim Verbindungsaufbau: " . htmlspecialchars($e->getMessage()));
    }
  }
    public function getDb() {
      return $this->db;
    }
    public function getAllEvents()
    {
      //\\ Implementierung wenn benötigt!
    }

    public function getEventsonDay($jahr, $monat, $tag)
    {
      $events = [];

      $select = $this->db->prepare("SELECT `id`, `anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `kategorieid`, `ort`
                                      FROM `termine`
                                      WHERE (YEAR(`anfang`) = :jahr AND MONTH(`anfang`) = :monat AND DAY(`anfang`) = :tag)
                                      ORDER BY `anfang` ASC");

      if ($select->execute([':jahr' => $jahr, ':monat' => $monat, ':tag' => $tag])) {
        $events = $select->fetchAll(PDO::FETCH_CLASS, 'Termin');
        //$events = $select->fetchAll();
      }

      return $events;

    } // Ende Funktion getEventsonDay()

    public function getKategorie($katid)
    {
      $kategorie = NULL;

      $select = $this->db->prepare("SELECT *
                                    FROM `kategorie`
                                    WHERE `id` = :katid");

      if ($select->execute([':katid' => $katid])) {
        $kategorie = $select->fetchObject('Kategorie');
      }

      return ($kategorie) ? $kategorie : NULL;

    } // Ende Funktion getKategorie()

    public function getEventsforCategory($category)
    {

    } // Ende Funktion getEventsforCategory()
}

?>