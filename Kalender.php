<?php

include "Database.php";

//include "EventFactory.php";

class Kalender
{
    private $monat;
    private $jahr;
    private $woche;
    private $tageDerWoche;
    private $anzahlTage;
    private $infoDatum;
   //private $infoDatumWeek = [];
    private $tagDerWoche;
    private $tag;
    private $startWeek;
    private $endWeek;
    private $timestamp_montag;
    private $timestamp_sonntag;
    public function __construct($monat, $jahr, $woche, $tageDerWoche = array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So',))
    {
        //$this->infoDatumWeek = [];
        $this->monat = $monat;
        $this->jahr = $jahr;
        $this->woche = $woche;
        $this->tageDerWoche = $tageDerWoche; // Wochentage als String
        $this->anzahlTage = cal_days_in_month(CAL_GREGORIAN, $this->monat, $this->jahr); // wieviel Tage gibt es in dem Monat
        $this->infoDatum = getdate(mktime(0, 0, 0, $monat, 1, $jahr));

        //minus 1 damit unser Kalender mit Montag beginnt und nicht mit Sonntag
        $this->tagDerWoche = $this->infoDatum['wday'] - 1; // Welcher Wochentag am 1. eines Monats?
        /*
         * Montag einer Woche
         */
        $this->timestamp_montag = strtotime("{$this->jahr}-W{$this->woche}");
        $this->startWeek = (int)date("d", $this->timestamp_montag);
        echo date("d", $this->timestamp_montag) . '</p>';

        /*
         * Sonntag einer Woche
         */
        $this->timestamp_sonntag = strtotime("{$this->jahr}-W{$this->woche}-7");
        $this->endWeek = (int)date("d", $this->timestamp_sonntag);
        echo (int)date("d", $this->timestamp_sonntag) . '</p>';

    }

    /**
     * Stellt einen Kalender in Monatsansicht dar
     */
    public function showMonth()
    {
                echo 'Show month';
        $ausgabe = '<table id="views">';
        $ausgabe .= '<caption><a href="index.php?m=' . ($this->monat - 1) . '&j=' . $this->jahr . '">vorheriger</a>&nbsp;' . $this->infoDatum['month'] . ' ' . $this->jahr . '&nbsp;<a href="index.php?m=' . ($this->monat + 1) . '&j=' . $this->jahr . '">n&auml;chster</a></caption>';

        $ausgabe .= '<thead><tr>';
        foreach ($this->tageDerWoche as $tag) {
            $ausgabe .= '<th>' . $tag . '</th>';
        }
        $ausgabe .= '</thead></tr>';

        $ausgabe .= '<tr>';

        // Weil unser Kalender am Montag und nicht am Sonntag beginnt
        if ($this->tagDerWoche == -1) {
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
            $evfactory = new EventFactory();
            $events = $evfactory->getEventsonDay($this->jahr, $this->monat, $tagCounter);
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

    } // Ende Funktion showMonth()

    /**
     * Stellt einen Kalender in Wochenansicht dar
     */
    public function showWeek()
    {
        /*
        [seconds] - seconds
        [minutes] - minutes
        [hours] - hours
        [mday] - day of the month
        [wday] - day of the week (0=Sunday, 1=Monday,...)
        [mon] - month
        [year] - year
        [yday] - day of the year
        [weekday] - name of the weekday
        [month] - name of the month
        [0] - seconds since Unix Epoch
         */



        echo 'Show week';
        $ausgabe = '<table id="views">';
        $ausgabe .= '<caption><a href="index.php?w=' . ($this->woche - 1) . '">vorherige</a>&nbsp;' . date("d.m.Y", $this->timestamp_montag). ' bis ' . date("d.m.Y", $this->timestamp_sonntag) . '&nbsp;<a href="index.php?w=' . ($this->woche + 1) .'">n&auml;chste</a></caption>';

        $ausgabe .= '<thead><tr>';
        foreach ($this->tageDerWoche as $tag) {
            $ausgabe .= '<th>' . $tag . '</th>';
        }
        $ausgabe .= '</thead></tr>';

        $ausgabe .= '<tr>';


        // Weil unser Kalender am Montag und nicht am Sonntag beginnt
        if ($this->tagDerWoche == -1) {
            $this->tagDerWoche = 0;
        }
        // Wenn der erste Tag des Monats nicht auf einen Montag faellt
        // Ersten Tage des Kalenders auffuellen
        if ($this->tagDerWoche > 0) {
            $ausgabe .= '<td colspan="' . $this->tagDerWoche . '"</td>';
        }
        //Tag-Counter
        $tagCounter = 1;
        $counter = $tagCounter;
        while ($this->startWeek <= $this->endWeek) {

            //Zuruecksetzen vom tagDerWoche Counter
            if ($this->tagDerWoche == 7) {
                $this->tagDerWoche = 0;
                $ausgabe .= '</tr><tr>';
            }
            echo "<br>";
            echo "counter: ".$counter;
            echo "<br>";
            echo "tagciounter: ".$tagCounter;
            echo "<br>";
            echo "tagderwoche: ".$this->tagDerWoche;
            echo "<br>";
            echo "startweek: ".$this->startWeek;
            echo "<br>";
            echo "endweek: ".$this->endWeek;
            echo "<br>";

            //\\ TODO: Klassenvergabe dynamisieren bzw. mittels Konstante für diese Klasse
            $ausgabe .= '<td><span class="nr">' . $tagCounter;

            //\\ Events anzeigen
            $evfactory = new EventFactory();
            $events = $evfactory->getEventsonDay($this->jahr, $this->monat, $tagCounter);
            foreach ($events as &$event) {
                $ausgabe .= $event->toHTML();
            }

            unset($event); // Entferne die Referenz auf das letzte Element

            $ausgabe .= '</span></td>';

            //counter hochzaehlen
            $tagCounter++;
            $this->tagDerWoche++;
            $counter++;
            $this->startWeek++;
        } // Ende While

        $ausgabe .= '<tbody id="event"></tbody>';

        $ausgabe .= '</tr></table>';

        echo $ausgabe;


    } // Ende Funktion showWeek()

    /**
     * Stellt einen Kalender in Tagesansicht dar
     */
    public function showDay()
    {
        echo 'Show day';
        $ausgabe = '<table id="views">';
        $ausgabe .= '<caption><a href="index.php?m=' . ($this->monat - 1) . '&j=' . $this->jahr . '">vorheriger</a>&nbsp;' . $this->infoDatum['month'] . ' ' . $this->jahr . '&nbsp;<a href="index.php?m=' . ($this->monat + 1) . '&j=' . $this->jahr . '">n&auml;chster</a></caption>';

        $ausgabe .= '<thead><tr>';
        foreach ($this->tageDerWoche as $tag) {
            $ausgabe .= '<th>' . $tag . '</th>';
        }
        $ausgabe .= '</thead></tr>';

        $ausgabe .= '<tr>';

        // Weil unser Kalender am Montag und nicht am Sonntag beginnt
        if ($this->tagDerWoche == -1) {
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
                //\\ TODO: Hier sollte das Objekt erzeugt werden und der HTML-Code des Termins per Funktionsaufrug zurückkommen
                /*$ausgabe .= '<div class="event"';
                if ($event['kategorieid'] > 0) $ausgabe .= ' style="border-left: Solid 12px ' . $event['farbe'] . '"';

                $ausgabe .= 'id="' . $event['id'] . '"';
                $ausgabe .= 'title="' . $event['beschreibung'] . '&#013;' . $event['ort'] . '"';
                $ausgabe .= '" onClick="zeigeEvent(' . $event['id'] . ')" ';
                $ausgabe .= '>' . $event['titel'] . '</div>';*/
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


    }

    /**
     * @return mixed
     */
    public function getMonat()
    {
        return $this->monat;
    }

    /**
     * @return mixed
     */
    public function getJahr()
    {
        return $this->jahr;
    }

    /**
     * @return int
     */
    public function getWoche(): int
    {
        return $this->woche;
    } // Ende Funktion showDay()

}

?>