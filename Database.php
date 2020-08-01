<?php

require_once "dbConf.php";
require_once "Category.php";

//Globale Varable für ein Datenbankobjekt.
$db = new Database(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);

class Database
{
    private $dbCon;

    public function __construct($db, $host, $user, $pass)
    {
        // Verbindung zur Database aufbauen
        try {
            $this->dbCon = new PDO("mysql:dbname=" . $db . ";host=" . $host . ";charset=utf8", $user, $pass);
            $this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Verbindung ueber PDO hergestellt.";
        } catch (PDOException $e) {
            exit("Fehler beim Verbindungsaufbau: " . htmlspecialchars($e->getMessage()));
        }
    }
    public function getDbCon()
    {
        return $this->dbCon;
    }

    public function select($sql) {
      $statement = $this->dbCon->prepare($sql);
                              
      if ($statement->execute()) {
        return $statement->fetchAll();
      }
    }

    public function insert($sql) {
      $statement = $this->dbCon->prepare($sql);
                              
      $statement->execute();
    }

    public function update($sql) {

    }

    public function delete($sql) {

    }

    public function getlastId() {
      return $this->dbCon->lastInsertId();
    }

    /**
     * Lädt alle Kategorien ins Dropdownmenue der editEvent.php
     */
    public function getAllKategorien() {
      $html = "";
      $sql = "SELECT * FROM `kategorie`";
      $ergebnis = $this->dbCon->query($sql);
      foreach($ergebnis as $zeile) {
          // "<option value=" . htmlspecialchars($zeile["id"]). ">" . htmlspecialchars($zeile["name"]) . "</option>";
          $html .= '<option value="' . htmlspecialchars($zeile["id"]). '">"' . htmlspecialchars($zeile["name"]) . '"</option>';
      }
      return $html;
  }

  public function getKategorie($katid)
    {
        $kategorie = NULL;

        $select = $this->dbCon->prepare("SELECT *
                                    FROM `kategorie`
                                    WHERE `id` = :katid");

        if ($select->execute([':katid' => $katid])) {
            $kategorie = $select->fetchObject('Category');
        }

        return ($kategorie) ? $kategorie : NULL;

    } // Ende Funktion getKategorie()
}

?>