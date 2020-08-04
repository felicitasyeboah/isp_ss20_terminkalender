<?php

require_once "dbConf.php";
require_once "Category.php";
require_once "Event.php";

//\\ Globale Varable für ein Datenbankobjekt.
$db = new Database(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);

/**
 * Database
 * 
 * Stellt grundlegende Funktionen zur Interaktion mit der Datenbank bereit
 */
class Database
{

    private $dbCon;

    /**
     * __construct
     * 
     * Aufbau der Verbindung zur, in diesem Fall, mySQL-Datenbank
     *
     * @param  string $db
     * @param  string $host
     * @param  string $user
     * @param  string $pass
     * @return void
     */
    public function __construct($db, $host, $user, $pass)
    {
        // Verbindung zur Database aufbauen
        try {
            $this->dbCon = new PDO("mysql:dbname=" . $db . ";host=" . $host . ";charset=utf8", $user, $pass);
            // diese Einstellungen sorgen dafür, dass bei Fehlern eine Exception geworfen wird
            $this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit("Fehler beim Verbindungsaufbau: " . htmlspecialchars($e->getMessage()) . '</br><a href=javascript:history.back();>zurück</a>');
        }
    }

    /**
     * Gibt die aktuelle Verbindung zurück.
     *
     * @return PDO
     */
    public function getDbCon()
    {
        return $this->dbCon;
    }

    /**
     * Sendet das übergebene SQL-Statement an die Datenbank.
     * 
     * Die Funktion erwartet ein select-Statement.
     * Nach der Ausführung wird das Ergebnis der Abfrage automatisch in ein Objekt der übergebenen Klasse umgewandelt.
     * Es ist zu beachten, dass mittels fetchAll immer ein Array gebildet wird, auch wenn nur ein Datensatz gefunden wird. 
     *
     * @param  string $sql
     * @param  string $class Name einer Klasse
     * @return Termin[]|Kategorie[]
     */
    public function select($sql, $class)
    {
        try {
            $statement = $this->dbCon->prepare($sql);

            if ($statement->execute()) {
                return $statement->fetchAll(PDO::FETCH_CLASS, $class);
            }
        } catch (PDOException $e) {
            echo "Fehler beim Lesen der Daten: " . htmlspecialchars($e->getMessage());
        }
    }

    /**
     * Sendet das übergebene SQL-Statement an die Datenbank.
     * 
     * Die Funktion erwartet ein insert-Statement.
     *
     * @param  string $sql
     * @return void
     */
    public function insert($sql)
    {
        try {
            $statement = $this->dbCon->prepare($sql);

            $statement->execute();
        } catch (PDOException $e) {
            echo "Fehler beim Einfuegen der Daten: " . htmlspecialchars($e->getMessage());
        }
    }

    /**
     * selectColor
     *
     * @param  mixed $sql
     * @return void
     */
    /*public function selectColor($sql)
    {
        $statement = $this->dbCon->prepare($sql);

        $statement->execute();
    }*/

    /**
     * Sendet das übergebene SQL-Statement an die Datenbank.
     * 
     * Die Funktion erwartet ein update-Statement.
     *
     * @param  string $sql
     * @return void
     */
    public function update($sql)
    {
        try {
            $statement = $this->dbCon->prepare($sql);

            $statement->execute();
        } catch (PDOException $e) {
            echo "Fehler beim Ändern der Daten: " . htmlspecialchars($e->getMessage());
        }
    }

    /**
     * Sendet das übergebene SQL-Statement an die Datenbank.
     * 
     * Die Funktion erwartet ein delete-Statement.
     *
     * @param  string $sql
     * @return void
     */
    public function delete($sql)
    {
        try {
            $statement = $this->dbCon->prepare($sql);

            $statement->execute();
        } catch (PDOException $e) {
            echo "Fehler beim Löschen der Daten: " . htmlspecialchars($e->getMessage());
        }
    }

    /**
     * Gibt die ID des zuletzt eingefügten Datensatzes zurück.
     * 
     * Diese Funktion wird nur an einer Stelle benutzt, nämlich direkt(!) nach dem Einfügen einer neuen Kategorie.
     *
     * @return string
     */
    public function getlastId()
    {
        return $this->dbCon->lastInsertId();
    }

    /**
     * Lädt alle Kategorien ins Dropdownmenue der editEvent.php.
     *
     * Diese Funktion gibt es neben der select-Funktion, da hier die Daten aufbereitet werden, um in der Dropdownlite angezeigt zu werden.
     * 
     * @return string 
     */
    public function getAllKategorien()
    {
        $html = "";
        $sql = "SELECT * FROM `kategorie`";
        $ergebnis = $this->dbCon->query($sql);
        foreach ($ergebnis as $zeile) {
            $html .= '<option value="' . htmlspecialchars($zeile["id"]) . '">"' . htmlspecialchars($zeile["name"]) . '"</option>';
        }
        return $html;
    }

    /**
     * Kategorie mittels ID laden.
     *
     * @param  int $katid
     * @return Kategorie|NULL
     */
    public function getKategorie($katid)
    {
        $kategorie = NULL;

        try {
            $select = $this->dbCon->prepare("SELECT *
                                    FROM `kategorie`
                                    WHERE `id` = :katid");

            if ($select->execute([':katid' => $katid])) {
                $kategorie = $select->fetchObject('Category');
            }
        } catch (PDOException $e) {
            echo "Fehler beim Holen der Kategorien: " . htmlspecialchars($e->getMessage());
        }

        return ($kategorie) ? $kategorie : NULL;
    }
}
