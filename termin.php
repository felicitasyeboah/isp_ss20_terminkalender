<?php

include "Datenbank.php";

class Termin
{
    private $monat;
    private $jahr;
    private $tageDerWoche;
    private $anzahlTage;
    private $infoDatum;
    private $tagDerWoche;

    private $db;

    public function __construct($db, $monat, $jahr, $tageDerWoche = array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So',))
    {
        $this->monat = $monat;
        $this->jahr = $jahr;
        $this->tageDerWoche = $tageDerWoche;
        $this->anzahlTage = cal_days_in_month(CAL_GREGORIAN, $this->monat, $this->jahr);
        $this->infoDatum = getdate(mktime(0, 0, 0, $monat, 1, $jahr));
        //minus 1 damit unser Kalender mit Montag beginnt und nicht mit Sonntag
        $this->tagDerWoche = $this->infoDatum['wday'] - 1;

        $this->db = $db;
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
            $events = $this->db->getEventsonDay($this->jahr, $this->monat, $tagCounter);
            foreach ($events as &$event) {
              //\\ TODO: Hier sollte das Objekt erzeugt werden und der HTML-Code des Termins per Funktionsaufrug zurückkommen
              $ausgabe .= '<div class="event"';
              $ausgabe .= 'id="' . $event['id'] . '"';
              $ausgabe .= 'title="' . $event['beschreibung'] . '&#013;' . $event['ort'] . '"';
              $ausgabe .= '>' . $event['titel'] . '</div>';
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
        $ausgabe .= '</tr></table>';

        echo $ausgabe;

    } // Ende Funktion show()
}

?>