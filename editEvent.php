<?php
require_once "Datenbank.php";
require_once "EventFactory.php";
$ausgabe = '';
//date('Y-m-d')
//date('H:i')
//date('H:i', time() + 3600)
if (isset($_POST["id"])) {
  $factory = new EventFactory();
  $termin = $factory->getEvent($_POST['id']);
  $tempid = $_POST["id"];

  $titel = ($termin->getTitel() !== null) ? $termin->getTitel() : "";
  //......
}
else {
  $plusEinTag = date('Y-m-d', time() + (60 * 60 * 24));
  $defaultTime = "00:00";
  $titel = isset($_POST["titel"]) ? $_POST["titel"] : "";
  $beschreibung = isset($_POST["beschreibung"]) ? $_POST["beschreibung"] : "";
  $anfangsdatum = isset($_POST["anfangsdatum"]) ? $_POST["anfangsdatum"] : date('Y-m-d');
  $anfangszeit = isset($_POST["anfangszeit"]) ? $_POST["anfangszeit"] : $defaultTime;
  $enddatum = isset($_POST["enddatum"]) ? $_POST["enddatum"] : $plusEinTag;
  $endzeit = isset($_POST["endzeit"]) ? $_POST["endzeit"] : $defaultTime;
  $ort = isset($_POST["ort"]) ? $_POST["ort"] : "";
  $tempid = "";
}


$formular = '<form id="kalender" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">
        <input type="text" style="display: none;" id="tempid" name="tempid" value="' . $tempid . '">
        <label>Titel:<var>* </var><br/><input size="65px" type="text" name="titel" value="' . $titel . '"><br><br></label>
        <label for="beschreibung">Beschreibung des Termins<var>* </var><br/>
            <textarea id="beschreibung" name=" beschreibung" rows="10" cols="60">' . $beschreibung . '</textarea>
        </label>
        <br>
        <br>
        <label>Anfangsdatum: <input size="50px" type="date" name="anfangsdatum" id="anfangsdatum" value="' . $anfangsdatum . '"></label>
        <label> Anfangszeit: <input size="50px" type="time" name="anfangszeit" id="anfangszeit" value="' . $anfangszeit . '"><br><br></label>
        <label>Enddatum: <input size="50px" type="date" name="enddatum" id="enddatum" value="' . $enddatum . '"></label>
        <label> Endzeit: <input size="50px" type="time" name="endzeit" id="endzeit" value="' . $endzeit . '"><br><br></label>
        <label for="ganztag">ganztaegig: <input size="50px" type="checkbox" name="ganztag" id="ganztag"
                                                value="1" checked onclick="setTime(this)"><br><br></label>
        <label>Ort:<var>* </var><input size="50px" type="text" name="ort" value="' . $ort . '"><br><br></label>

        <label>Kategorie: <input type="text" name="kategorie" list="kategorieName" oninput="loadColor(this.value)"/>
            <datalist id="kategorieName">
            //Vorhandende Kategorien werden aus der Datenbank geladen' . $db->getAllKategorien() . '
                 
            </datalist>
        </label>

        <label for="farbe"> Farbe:</label>
        <input type="color" id="farbe" name="farbe" value="#ffffff">
        <br/>
        <br/>
        <br/>
        <br/>
        <!--<button type="reset" name="resetbutton" value="Formular zur&uuml;cksetzen">Formular zur&uuml;cksetzen</button>-->
        <button type="submit" name="sendEventBtn" value="absenden">Absenden</button>&nbsp;&nbsp;
        <input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')">
    </form><p></p>';
if ($_SERVER['REQUEST_METHOD'] == "POST") {

//Formular auf sinnvolle/korrekte Eingaben ueberpruefen

    $regexTitel = '/^[a-zA-ZüöäÜÖÄß0-9\s\-.!\?]+$/i';
    $regexOrt = '/^[a-zA-ZüöäÜÖÄß\s\-]+$/i';
    if (empty($titel)) {
        $ausgabe = 'Bitte einen Titel eingeben.' . $formular;
    } elseif (!preg_match($regexTitel, $_POST['titel'])) {
        echo "titel: " . $_POST['titel'];
        $ausgabe = 'Bitte nur Buchstaben und Zahlen im Titel eingeben.' . $formular;
    } elseif (!empty($ort) && (!preg_match($regexOrt, $_POST['ort']))) {
        echo "Ort: " . $_POST['ort'];
        $ausgabe = 'Ort darf nur Buchstaben und "-" enthalten,' . $formular;
    } elseif (strlen($_POST["beschreibung"]) > 500) {
        echo 'Beschreibung ist zu lang. Max. 500 Zeichen.'. $formular;

        //Wenn Eingaben in Ordnung
    } else {
        if (isset($_POST['ganztag'])) {
            $tmpGanztag = 1;

        } else {
            $tmpGanztag = 0;
        }

        //Datum und Zeit für Eintrag in Datenbank vorbereiten
        $anfang = $_POST['anfangsdatum'] . " " . $_POST['anfangszeit'] . ":00";
        echo $anfang;
        $ende = $_POST['enddatum'] . " " . $_POST['endzeit'] . ":00";
        echo $ende;
        $factory = new EventFactory();
        $termin = $factory->createEvent($_POST['titel'], $_POST['beschreibung'], $anfang, $ende, $_POST['ort'], $_POST['kategorie'], $_POST['farbe'], $tmpGanztag);

        
        //\\ wurde eine neue Kategorie eingegeben?
        if ($termin->getKategorie() == null && !empty($_POST['kategorie'])) {
            $termin->addKategorie($_POST['kategorie'], $_POST['farbe']);
        }

        $termin->addEvent();
        $ausgabe .= '<h3> ' . htmlspecialchars($termin->getTitel()) . ' wurde eingetragen!</h3>' . $formular;
        //}
    }
} else {
    $ausgabe = $formular;
}

include("inc/header.inc.php");
echo $ausgabe;
include "inc/footer.inc.php";
?>
<!--<script>
    // Das XMLHttpRequest-Objekt setzen
    var xhr = new XMLHttpRequest();

    // Diese Funktion beim laden der Seite starten
    window.addEventListener("load", function () {

        // Dem HTML-Button (name="senden") den Event-Handler: "click" zuweisen,
        // dieser ruft dann (beim klicken) die Funktion: eintragen() auf.
        document.getElementsByName("sendEventBtn")[0].addEventListener("click", eintragen);
    });

    function eintragen() {
        // Die Formulardaten holen
        var daten = new FormData(document.getElementsByTagName("form")[0]);

        // Die aktuelle Datei über dem HTTP-Stream öffnen
        xhr.open("POST", document.URL, true);

        // Die Formulardaten senden
        xhr.send(daten);

        // Auf eine Antwort warten
        xhr.onreadystatechange = function () {

            // Daten werden (vom PHP-Script) empfangen
            if (xhr.readyState == 4 &&
                xhr.status == 200) {

                // Den Inhalt von 'responseText' überprüfen
                if (xhr.responseText == "OK") {

                    // Eine Meldung ausgeben
                    document.getElementsByTagName("form")[0].innerHTML = 'Die Daten wurden gesendet.';
                } else {

                    // Die Daten in einzelne Objekte zerlegen
                    var objekt = JSON.parse(xhr.responseText);

                    // Die HTML-Elemente (var) mit den Daten befüllen
                    document.getElementsByTagName("var")[0].innerHTML = objekt.titel;
                    document.getElementsByTagName("var")[1].innerHTML = objekt.beschreibung;
                    document.getElementsByTagName("var")[2].innerHTML = objekt.ort;
                }
            }
        }
    }
</script>-->

<!-- Java Scripte -->
<script>
    // setzt vor einstelligen Ziffer eine fuehrende 0 voran
    function fuehrendeNull(zahl) {
        zahl = (zahl < 10 ? '0' : '') + zahl;
        return zahl;
    }

    //aendert den vorausgewählten Zeitraum, wenn ganztag an- und abgewählt wird
    function setTime(element) {
        var jetzt = new Date();
        var inEinerStunde = new Date();
        var morgen = new Date();

        //Aktuelles Datum
        var datum = jetzt.getFullYear() + "-" + fuehrendeNull(jetzt.getMonth() + 1) + "-" + fuehrendeNull(jetzt.getDate());

        //aktuelle Zeit
        var uhrzeit = fuehrendeNull(jetzt.getHours()) + ":" + fuehrendeNull(jetzt.getMinutes());

        //setzt die aktuelle Uhrzeit plus eine Stunde
        inEinerStunde.setHours(inEinerStunde.getHours() + 1)
        var uhrzeitPlus = fuehrendeNull(inEinerStunde.getHours()) + ":" + fuehrendeNull(inEinerStunde.getMinutes());

        //setz den aktuellen Tag einen Tag weiter
        morgen.setDate(morgen.getDate() + 1)
        var datumPlus = morgen.getFullYear() + "-" + fuehrendeNull(morgen.getMonth() + 1) + "-" + fuehrendeNull(morgen.getDate());

        if (element.checked) {
            document.getElementById("anfangszeit").setAttribute("value", "00:00")
            document.getElementById("endzeit").setAttribute("value", "00:00")
            document.getElementById("anfangsdatum").setAttribute("value", datum);
            document.getElementById("enddatum").setAttribute("value", datumPlus);

        } else {
            document.getElementById("anfangszeit").setAttribute("value", uhrzeit);
            document.getElementById("endzeit").setAttribute("value", uhrzeitPlus);
            document.getElementById("anfangsdatum").setAttribute("value", datum);
            document.getElementById("enddatum").setAttribute("value", datum);
        }
        console.log('geht');
    }

   //Farbe wird passen zu ausgewählten Kategorie aktualisiert (mit Ajax)
    function loadColor(id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("farbe").setAttribute("value", this.responseText);
            }
        };
        xhttp.open("GET", "ajax.php?color=" + id, true);
        xhttp.send();
    }
</script>