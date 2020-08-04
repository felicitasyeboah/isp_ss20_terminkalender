<?php
require_once("dbConf.php"); // Einmaliges einbinden der Datenbank Konfigurationsdatei
include "Kalender.php"; // Einbinden der Kalender Klasse
include("inc/header.inc.php"); // Einbinden der Header-Datei mit html-Konstrukt

echo '<div id="container">';

// Kalender aufbauen und anzeigen
$monat = (int)date('m'); // Aktueller Monat
$jahr = (int)date('Y'); // Aktuelles Jahr
$woche = (int)date('W'); // aktuelle Woche

// Wenn nicht der aktueller Monat oder Jahr im Kalender  ausgewählt wurde,
// wird $monat und $jahr anhand der Get-Variablen gesetzt
if (isset($_GET['m'])) {
    $monat = $_GET['m'];
    $jahr = $_GET['j'];
    // Bei Jahreswechsel in ein früeheres Jahr wird der $monat auf 12 (Dezember gesetzt)
    // und $jahr um eins dekrementiert
    if ($_GET['m'] == 0) {
        $monat = 12;
        $jahr = $jahr - 1;
    }
    // Bei Jahreswechsel in ein früeheres Jahr wird der $monat auf 1 (Januar gesetzt)
    // und $jahr um eins inkrementiert
    if ($_GET['m'] == 13) {
        $monat = 1;
        $jahr = $jahr + 1;
    }
}
// setzt $woche auf die übergebene Woche
if (isset($_GET['w'])) {
    $woche = $_GET['w'];
}
// Neues Kalender-Objekt wird erzeugt. Übergeben werden Monat, Jahr und Woche.
$kalender = new Kalender($monat, $jahr, $woche);

// wurde ein Kategoriefilter ausgewählt?
if (isset($_GET['kat'])) {
    // Nach welcher Kategorie soll gefilter werden?
    $kalender->setKatFilter($_GET['kat']);
}
// Wurde eine Ansicht zum Filtern ausgewaehlt?
if (isset($_GET['ansicht'])) {
    // Nach welcher Ansicht soll gefiltert werden?
    $kalender->setAnsicht($_GET['ansicht']);
    switch ($_GET['ansicht']) {
        case 'Monat':
            $kalender->showMonth();
            break;
        case 'Woche':
            $kalender->showWeek();
            break;
    }
} else {
    // wurde kein Filter ausgewählt, Zeige die Monatsansicht des Kalenders mit allen Temrinen an.
    $kalender->setAnsicht("Monat");
    $kalender->showMonth();
}

echo '</div>';
echo '<p></p>';

include "inc/footer.inc.php"; // Einbinden der Footer-Datei mit html-Konstrukt
?>

<!-- Javascript Funktionen -->
<script>

    const verzeichnis = "";
    const event = verzeichnis + "Event.php";
    const kalender = verzeichnis + "Kalender.php";
    const XHR = new XMLHttpRequest();

    /**
     * Der ausgewaehlte Termin wird anhand seiner ID mit seinen Details aufgerufen und angezeigt
     * @param id
     */
    function zeigeEvent(id) {
        XHR.open("GET", event +
            "?details&id=" + id, true);
        XHR.send(null);
        XHR.onload = ausgabe; // ruft Funktion ausgabe() auf und ersetzt das Element  mit der ID "event" mit den Details des ausgewaehlten Termins
        XHR.onerror = function () {
            console.log('Error: ' + xhr.status); // Ein Fehler ist aufgetreten
        }
    }

    /**
     * Gibt den zurueckgegebenen Text des Ajax Aufrufes aus zeigeEvent(id) aus.
     */
    function ausgabe() {

        if (XHR.readyState == 4 && XHR.status == 200) {
            document.getElementById("event").innerHTML = this.responseText;
        }
    }

    /**
     * Blendet Filterbar ein und aus
     */
    function zeigeFilter() {
        var element = document.getElementById("filter");
        element.classList.toggle("invisible");
    }

    /**
     * Übergibt die ausgewaehlte Kategorie (value) an die editCategory.php
     */
    function bearbeiteKategorie() {
        var kat = document.getElementById("kategorie").value;
        var params = (kat !== '') ? '&id=' + kat + '' : '';
        window.location.replace('editCategory.php\?' + params);
    }

    /**
     * Setzt den Filter fuer Kategorie und Ansicht anhand der uebergebenen Parameter (jahr, monat, woche)
     * @param j // Jahr
     * @param m // Monat
     * @param w // Woche
     */
    function startFilter(j, m, w) {
        var kat = document.getElementById("kategorie").value;
        var show = document.querySelector('input[name="ansicht"]:checked').value;
        var params = 'm=' + m + '&j=' + j + '&w=' + w;
        params += (kat !== '') ? '&kat=' + kat + '' : '';
        params += (show !== '') ? '&ansicht=' + show + '' : '';
        window.location.replace('index.php\?' + params);
    }
</script>


