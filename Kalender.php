<?php

include "Datenbank.php";

class Kalender
{
    private $monat;
    private $jahr;
    private $tageDerWoche;
    private $anzahlTage;
    private $infoDatum;
    private $tagDerWoche;

    public function __construct($monat, $jahr, $tageDerWoche = array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So',))
    {
        $this->monat = $monat;
        $this->jahr = $jahr;
        $this->tageDerWoche = $tageDerWoche;
        $this->anzahlTage = cal_days_in_month(CAL_GREGORIAN, $this->monat, $this->jahr);
        $this->infoDatum = getdate(mktime(0, 0, 0, $monat, 1, $jahr));
        //minus 1 damit unser Kalender mit Montag beginnt und nicht mit Sonntag
        $this->tagDerWoche = $this->infoDatum['wday'] - 1;
    }
    /**
     * Baut eine Verbindung zur MYSQL Datenbank auf und gibt diese Verbindung zurück
     * @return PDO
     */
    function linkDB()
    {
        try {
            $db = new PDO("mysql:dbname=" . MYSQL_DB . ";host=" . MYSQL_HOST . ";charset=utf8", MYSQL_BENUTZER, MYSQL_KENNWORT);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Verbindung ueber PDO hergestellt.";
            return $db;
        } catch (PDOException $e) {
            //echo "Fehler: " . htmlspecialchars($e->getMessage());
            exit("Fehler beim Verbindungsaufbau: " . htmlspecialchars($e->getMessage()));
        }
    }

    /**
     * Traegt einen Temrin in den Kalender ein
     * @param Termin $termin
     */
    public function addEvent(Termin $termin) {

        try {
            $db_con = self::linkDB(); // die mit der function linkDB() aufgebaute Verbindung zur DB wird in $db_con gespeichtert
            $sql = "INSERT INTO `kalender` (`anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorie`, `farbe`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; //SQL Statement
            $kommando = $db_con->prepare($sql); //SQL Statement wird vorbereitet
            $kommando->execute(array($termin->getStart(), $termin->getEnde(), $termin->getGanztaegig(), $termin->getTitel(), $termin->getBeschreibung(), $termin->getOrt(), $termin->getKategorie(), $termin->getFarbe()));

            echo "Termin wurde in der Datenbank eingetragen.<br/>";
            $db_con = null; // Verbindung zur DB wird geschlossen
        } catch (Exception $e) { // Wenn ein Fehler beim Eintragen des Buches in die DB auftritt, wird er im Catchblock
            // gecatched und der Fehler ausgegeben.
            echo "Fehler: " . $e->getMessage();
        }
    }

    public function show()
    {
        $ausgabe = '<table>';
        $ausgabe .= '<caption><a href="index.php?m=' . ($this->monat-1) . '&j=' . $this->jahr . '">vorheriger</a>&nbsp;' . $this->infoDatum['month'] . ' ' . $this->jahr . '&nbsp;<a href="index.php?m=' . ($this->monat+1) . '&j=' . $this->jahr . '">n&auml;chster</a></caption>';

        $ausgabe .= '<thead><tr>';
        foreach ($this->tageDerWoche as $tag) {
            $ausgabe .= '<th>' . $tag . '</th>';
        }
        $ausgabe .= '</thead></tr>';

        $ausgabe .= '<tr>';

        // Weil unser Kalender am Montag und nicht am Sonntag beginnt
        if($this->tagDerWoche == -1) {
            $this->tagDerWoche = 0;
        }
        // Wenn der erste Tag des Monats nicht auf einen Montag faellt
        // Ersten Tage des Kalenders auffuellen
        if ($this->tagDerWoche > 0) {
            $ausgabe .= '<td colspan="' . $this->tagDerWoche . '"</td>';
        }
        //Tag-Counter
        $tagCounter = 1;


        while ($tagCounter <= $this->anzahlTage) {

            //Zuruecksetzen vom tagDerWoche Counter
            if ($this->tagDerWoche == 7) {
                $this->tagDerWoche = 0;
                $ausgabe .= '</tr><tr>';
            }

            //\\ TODO: Klassenvergabe dynamisieren bzw. mittels Konstante für diese Klasse
            $ausgabe .= '<td><span class="nr">' . $tagCounter;

            //\\ Events anzeigen
            $events = $GLOBALS["db"]->getEventsonDay($this->jahr, $this->monat, $tagCounter);
            foreach ($events as &$event) {
              $ausgabe .= $event->toHTML();
            }

            unset($event); // Entferne die Referenz auf das letzte Element

            $ausgabe .= '</span></td>';

            //counter hochzaehlen
            $tagCounter++;
            $this->tagDerWoche++;
        } // Ende While

        //resliche Tage im Kalender am Ende auffuellen, wenn der letzte Tag
        //des Monats nicht auf das Ende des Kalenders faellt
        if ($this->tagDerWoche != 7) {
            $restlicheTage = 7 - $this->tagDerWoche;
            $ausgabe .= '<td colspan="' . $restlicheTage . '"</td>';
        }

        $ausgabe .= '<tbody id="event"></tbody>';

        $ausgabe .= '</tr></table>';

        echo $ausgabe;

    } // Ende Funktion show()
}

?>