<?php

include "Database.php"; // Einbinden der Database.php

/**
 * Die Klasse Kalender erstellt ein Kalender-Objekt. Sie enthaelt Funktionen zur Oberfläche des Kalenders wie z.b.
 * die Wochen- und Monatsansicht, sowie die Navigationsbar für die Filter und zum hinzufuegen eines neuen Termins.
 *
 */
class Kalender
{
    /**
     * @var int $monat ausgewaehlter Monat
     * @var int $jahr ausgewaehltes Jahr
     * @var int $woche ausgewaehlte Woche
     * @var string[] $tageDerWoche Ueberschriften der Wochentage des Kalenders
     * @var int $anzahlTage Anzahl der Tage des ausgewählten Monats.
     * @var array $infoDatum Speichert Sekunden, Minuten, Stunden, Tag der Woche, Monat, Jahr, Tag des Jahres, Name der Woche,
     *                          Name des Monats, Sekunden seit Unix Epoche für den 1. des ausgewaehlten Monats.
     *
     * @var int $tagDerWoche Wochentag, auf den der 1. des ausgewaehlten Monats faellt als int // Mo = 0, Di = 1, Mi = 2, Do = 3, Fr = 4, Sa = 5, So = -1/6
     * @var false|int $timestamp_montag Zeitstempel des Beginns (Montags) der ausgewaehlten Woche
     * @var false|int $timestamp_sonntag Zeitstempel des Endes (Sonntag) der ausgewaehlten Woche
     * @var int $startWeek Erster Tag der Woche als Zahl
     * @var int $katFilter ausgewaehlte KategorieId
     * @var string $ansicht Ansicht (Wochen- oder Monatsansicht) des Kalenders, die Angezeigt wird.
     *
     */
    private $monat;
    private $jahr;
    private $woche;
    private $tageDerWoche;
    private $anzahlTage;
    private $infoDatum;
    private $tagDerWoche;
    private $timestamp_montag;
    private $timestamp_sonntag;
    private $startWeek;
    private $katFilter;
    private $ansicht;

    /**
     * Erstellt ein Kalender-Objekt aus uebergebener Woche, Monat, Jahr und Wochentagen.
     * @param int $monat Uebergebener Monat
     * @param int $jahr Uebergebenes Jahr
     * @param int $woche Uebergebene Woche
     * @param string[] $tageDerWoche Wochentage
     */
    public function __construct($monat, $jahr, $woche, $tageDerWoche = array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So',))
    {
        $this->monat = $monat;
        $this->jahr = $jahr;
        $this->woche = $woche;
        $this->tageDerWoche = $tageDerWoche; // Wochentage als String
        $this->anzahlTage = cal_days_in_month(CAL_GREGORIAN, $this->monat, $this->jahr); // wieviel Tage gibt es in dem Monat
        $this->infoDatum = getdate(mktime(0, 0, 0, $monat, 1, $jahr)); // Informationen zum 1. des uebergebenen Monats

        // Welcher Wochentag am 1. eines Monats?
        $this->tagDerWoche = $this->infoDatum['wday'] - 1;  //wday liefert ein int zurueck, wobei 0 für Sonntag, 1 für Montag usw. steht.
        //minus 1 weil unser Kalender mit Montag beginnen soll // Mo = 0, Di = 1, Mi = 2, Do = 3, Fr = 4, Sa = 5, So = -1/6

        // Montag einer Woche
        $this->timestamp_montag = strtotime("{$this->jahr}-W{$this->woche}");
        $this->startWeek = (int)date("d", $this->timestamp_montag); //Erster Tag der Woche als Zahl

        //Sonntag einer Woche
        $this->timestamp_sonntag = strtotime("{$this->jahr}-W{$this->woche}-7");
    }

    /**
     * HTML-Kontrukt zum Anzeigen des Filter- und Termin-hinzufuegen-Icons sowie zum Ausklappen der Filter- und Editkategorieleiste
     *
     * @return string
     */
    private function addNavBar()
    {
        $kategorie = $GLOBALS["db"]->getAllKategorien(); //holt alle vorhandenen Kategorien aus der Datenbank

        $navBar = '<div class="infobar nav eventLink ico_filter" onclick="zeigeFilter()"></div>'; //\\ Toggle-Icon
        $navBar .= '<div class="infobar nav eventLink ico_add" onclick="window.location.replace(\'editEvent.php\')"></div>'; //\\ Toggle-Icon
        $navBar .= '<div id="filter" class="invisible">';
        $navBar .= '<div class="filterbar">';
        $navBar .= '<label>Kategorie: <input type="text" id="kategorie" list="kategorieName" value="' . $this->katFilter . '">
          <datalist id="kategorieName"> ' . $kategorie . '</datalist></label>';
        $navBar .= '<div class="infobar eventLink ico_edit" onclick="bearbeiteKategorie()"></div>';
        $navBar .= '<div class="infobar eventLink ico_for" onclick="startFilter(' . $this->jahr . ',' . $this->monat . ',' . $this->woche . ')"></div>';
        $navBar .= '</div>';
        $navBar .= '<div class="ansichtbar">';
        $navBar .= '<input type="radio" id="monat" name="ansicht" value="Monat" ';
        if ($this->ansicht == "Monat") $navBar .= 'checked';
        $navBar .= '>';
        $navBar .= '<label for="monat"> Monat</label> ';
        $navBar .= '<input type="radio" id="woche" name="ansicht" value="Woche" ';
        if ($this->ansicht == "Woche") $navBar .= 'checked';
        $navBar .= '>';
        $navBar .= '<label for="woche"> Woche</label>';
        $navBar .= '</div>';

        return $navBar;

    }

    /**
     * Stellt den Kalender in der Monatsansicht dar
     */
    public function showMonth()
    {
        $ausgabe = '<table id="views">';
        $ausgabe .= '<caption><div class="infobar eventLink ico_back" onclick="window.location.replace(\'index.php?m=' .
            ($this->monat - 1) . '&j=' . $this->jahr . '&kat=' . $this->katFilter . '\')"></div><div id="zeitinfo">&nbsp;' . $this->infoDatum['month'] .
            ' ' . $this->jahr . '&nbsp;</div><div class="infobar eventLink ico_for" onclick="window.location.replace(\'index.php?m=' .
            ($this->monat + 1) . '&j=' . $this->jahr . '&kat=' . $this->katFilter . '\')"></div>';

        // Navigation (Filter, Ansichten)
        $ausgabe .= $this->addNavBar();

        $ausgabe .= '</caption>';

        $ausgabe .= '<thead><tr>';
        //Erstelle für jeden Tag einer Woche (Mo, Di, Mi,...) eine Tabellenueberschrift
        foreach ($this->tageDerWoche as $tag) {
            $ausgabe .= '<th>' . $tag . '</th>';
        }
        $ausgabe .= '</thead></tr>';

        $ausgabe .= '<tr>';

        // Wenn der erste Tag des Monats($tagDerWoche) auf einen Sonntag (=-1) fällt, setze tagDerWoche auf 6
        // Mo = 0, Di = 1, Mi = 2, Do = 3, Fr = 4, Sa = 5, So = -1/6
        if ($this->tagDerWoche == -1) {
            $this->tagDerWoche = 6;
        }
        // Wenn der erste Tag des Monats nicht auf einen Montag faellt
        // Ersten Tage des Kalenders auffuellen ( Montag gleich bedeutend mit 0)
        // Wenn 1. Tag des Monats ein Montag, dann colspan = 0 = keine leeren Felder;
        if ($this->tagDerWoche > 0) {
            $ausgabe .= '<td colspan="' . $this->tagDerWoche . '"</td>';
        }
        // Tag-Counter
        $tagCounter = 1;

        // Solange nicht die Anzahl der Tage des jeweiligen Monats erreicht ist
        while ($tagCounter <= $this->anzahlTage) {

            // Zuruecksetzen vom tagDerWoche Counter, damit nach 7 Tagen eine neue Reihe angefangen wird
            if ($this->tagDerWoche == 7) {
                $this->tagDerWoche = 0;
                $ausgabe .= '</tr><tr>';
            }
            // Zelle des jeweiligen Tages des Monats
            $ausgabe .= '<td><span class="nr">' . $tagCounter;

            //\\ Events anzeigen
            $evfactory = new EventFactory();
            // Holt alle Events des Tages
            $events = $evfactory->getEventsonDay($this->jahr, $this->monat, $tagCounter, $this->katFilter);
            // Gibt alle Events des Tages am aktuellen Tag aus
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
        $ausgabe = '<table id="views">';
        $ausgabe .= '<caption><div class="infobar eventLink ico_back" onclick="startFilter(' . $this->jahr . ',' .
            $this->monat . ',' . ($this->woche - 1) . ')"></div><div id="zeitinfo">&nbsp;' . date("d.m.Y", $this->timestamp_montag) .
            ' bis ' . date("d.m.Y", $this->timestamp_sonntag) . '&nbsp;</div><div class="infobar eventLink ico_for" onclick="startFilter(' .
            $this->jahr . ',' . $this->monat . ',' . ($this->woche + 1) . ')"></div>';

        // Navigation (Filter, Ansichten)
        $ausgabe .= $this->addNavBar();

        $ausgabe .= '<thead><tr>';
        // Erstelle für jeden Tag einer Woche (Mo, Di, Mi,...) eine Tabellenueberschrift
        foreach ($this->tageDerWoche as $tag) {
            $ausgabe .= '<th>' . $tag . '</th>';
        }
        $ausgabe .= '</thead></tr>';

        $ausgabe .= '<tr>';

        // Tag-Counter
        $tagCounter = $this->startWeek; // Der Tag als Zahl an dem die Woche(der Montag der Woche) beginnt
        $date = new DateTime();
        $counter = 1;
        while ($counter <= 7) {
            if ($tagCounter > $this->anzahlTage) {
                $tagCounter = 1;
            }

            //\\ Anhand der Kalenderwoche den Monat bestimmen ($counter ist ein Offset, um auch den Monatsübergang zu erwischen)
            $date->setISODate($this->jahr, $this->woche, $counter);
            $monat = $date->format('n');
       
            $ausgabe .= '<td><span class="nr">' . $tagCounter;

            //\\ Events anzeigen
            $evfactory = new EventFactory();
            // Holt alle Events des Tages
            $events = $evfactory->getEventsonDay($this->jahr, $monat, $tagCounter, $this->katFilter);
            // Gibt alle Events des Tages am aktuellen Tag aus
            foreach ($events as &$event) {
                $ausgabe .= $event->toHTML();
            }
            unset($event); // Entferne die Referenz auf das letzte Element

            $ausgabe .= '</span></td>';

            $tagCounter++;
            $counter++;
        } // Ende While

        $ausgabe .= '<tbody id="event"></tbody>';
        $ausgabe .= '</tr></table>';

        echo $ausgabe;

    } // Ende Funktion showWeek()

    /**
     * Stellt einen Kalender in einer funktionierenden ;) Wochenansicht dar
     */
    public function showWeekcorrected()
    {
        $date = new DateTime();

        //\\ ein paar Variablen vorbereiten

        //\\ Anfangszeit bestimmen
        $date->setISODate($this->jahr, $this->woche);
        $amonat = $date->format('n');
        $ajahr  = $date->format('Y');
        $aTag  = $date->format('j');
        $adateausgabe = $date->format("d.m.Y");

        //\\ Endezeit bestimmen
        $date->setISODate($this->jahr, $this->woche, 7);
        $emonat = $date->format('n');
        $ejahr  = $date->format('Y');
        $eTag  = $date->format('j');
        $edateausgabe = $date->format("d.m.Y");

        //\\ Steuerungsvariable
        //$fwoche = ($ejahr > $ajahr) ? 1 : ($this->woche + 1);
        $maxCW = $this->getLastCw($ejahr);
        $fwoche = $this->woche + 1;
        if($fwoche > $maxCW)
        {
          $fwoche = 1;
          $ejahr += 1;
          $monat = 1;
        }
        $bwoche = $this->woche - 1;
        if($bwoche <= 0)
        {
          $bwoche = $this->getLastCw($ajahr);
          $ajahr -= 1;
          $monat = 12;
        }
        //$bwoche = ($ejahr > $ajahr) ? $maxCW : ($this->woche - 1);

        $ausgabe = '<table id="views">';
        $ausgabe .= '<caption><div class="infobar eventLink ico_back" onclick="startFilter(' . $ajahr . ',' .
            $amonat . ',' . $bwoche . ')"></div><div id="zeitinfo">&nbsp;' . $adateausgabe .
            ' bis ' . $edateausgabe . '&nbsp;</div><div class="infobar eventLink ico_for" onclick="startFilter(' .
            $ejahr . ',' . $emonat . ',' . $fwoche . ')"></div>';
        /*$ausgabe .= '<caption><div class="infobar eventLink ico_back" onclick="startFilter(' . $ajahr . ',' .
            $amonat . ',' . $bwoche . ')"></div><div id="zeitinfo">&nbsp;' . date("d.m.Y", $date->setISODate($this->jahr, $this->woche)) .
            ' bis ' . date("d.m.Y", $date->setISODate($this->jahr, $this->woche, 7)) . '&nbsp;</div><div class="infobar eventLink ico_for" onclick="startFilter(' .
            $ejahr . ',' . $emonat . ',' . $fwoche . ')"></div>';*/

        // Navigation (Filter, Ansichten)
        $ausgabe .= $this->addNavBar();

        $ausgabe .= '<thead><tr>';
        // Erstelle für jeden Tag einer Woche (Mo, Di, Mi,...) eine Tabellenueberschrift
        foreach ($this->tageDerWoche as $tag) {
            $ausgabe .= '<th>' . $tag . '</th>';
        }
        $ausgabe .= '</thead></tr>';

        $ausgabe .= '<tr>';

        // Tag-Counter
        $tagCounter = $aTag; // Der Tag als Zahl an dem die Woche(der Montag der Woche) beginnt
        $counter = 1;
        $monat = $amonat;
        $jahr = $ajahr;
        while ($counter <= 7) {
            if ($tagCounter > $this->anzahlTage) {
                $tagCounter = 1;
                $monat = $emonat;
                $jahr = ($ajahr != $ejahr) ? $ajahr : $ejahr;
            }

            $ausgabe .= '<td><span class="nr">' . $tagCounter;

            //\\ Events anzeigen
            $evfactory = new EventFactory();
            // Holt alle Events des Tages
            $events = $evfactory->getEventsonDay($jahr, $monat, $tagCounter, $this->katFilter);
            // Gibt alle Events des Tages am aktuellen Tag aus
            foreach ($events as &$event) {
                $ausgabe .= $event->toHTML();
            }
            unset($event); // Entferne die Referenz auf das letzte Element

            $ausgabe .= '</span></td>';

            $tagCounter++;
            $counter++;
        } // Ende While

        $ausgabe .= '<tbody id="event"></tbody>';
        $ausgabe .= '</tr></table>';

        echo $ausgabe;

    } // Ende Funktion showWeek()

    private function getLastCw($jahr)
    {
        $date = new DateTime;
        $date->setISODate($jahr, 53);
        return ($date->format("W") === "53" ? 53 : 52);
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
    }

    public function setKatFilter($katId)
    {
        $this->katFilter = $katId;
    }

    public function setAnsicht($ansicht)
    {
        $this->ansicht = $ansicht;
    }

}

?>