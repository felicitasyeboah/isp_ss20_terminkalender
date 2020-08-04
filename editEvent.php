<?php
require_once "Database.php";
require_once "EventFactory.php";
$ausgabe = '';

$defaultTime = "00:00"; // Standardstartzeit, die beim Setzen eines Ganztages ausgewählt wird
$ganztagTime = "23:59"; // Standardendzeit, die beim Setzen eines Ganztages ausgewählt wird

/*
 * Es wird geprüft, ob beim Ausführen der Seite eine GET-Variable mitgesendet wurde. Anhand dessen wird geprüft, ob ein
 * vorhandener Termin bearbeitet wird oder ob ein neuer Termin erstellt wird.
 *
 * Wenn ein Termin bearbeitet wird, wird dessen ID mitgegeben. Hier wird geprüft, ob eine ID vorhanden ist.
 * Wenn ja, dann:
 */
if (isset($_GET["id"])) {
    $factory = new EventFactory(); //erstelle neue Fabrik
    $termin = $factory->getEventbyId($_GET["id"]); // Hole den Termin anhand der uebergebenen ID
    $tempid = $_GET["id"]; // Setze uebergebene ID temporaer
    // Lade das Formular mit allen zu dem Termin bekannten Werten vor
    $group = ($termin->isGroupEvent()) ? $termin->getGruppe() : "";
    $titel = ($termin->getTitel() !== null) ? $termin->getTitel() : "";
    $beschreibung = ($termin->getBeschreibung() !== null) ? $termin->getBeschreibung() : "";
    $anfangsdatum = ($termin->getAnfangsdatum() !== null) ? $termin->getAnfangsdatum() : date('Y-m-d');
    $anfangszeit = ($termin->getAnfangszeit() !== null) ? $termin->getAnfangszeit() : $defaultTime;
    $enddatum = ($termin->getEnddatum() !== null) ? $termin->getEnddatum() : date('Y-m-d');
    $endzeit = ($termin->getEndzeit() !== null) ? $termin->getEndzeit() : $ganztagTime;
    $ort = ($termin->getOrt() !== null) ? $termin->getOrt() : "";
    $kategorie = $db->getAllKategorien();
    $katVal = ($termin->getKategorieId() !== null) ? $termin->getKategorieId() : "";
    $katId = $termin->getKategorieId();
    if(!empty($katId)) {
        $katColor = $termin->getKategorie()->getFarbe();
    } else {
        $katColor = "#ffffff";
    }
/*
 * Wenn keine ID mitgesendet wurde, dann wird ein neuer Termin vorbereitet:
 */
} else {
    // Variablen, die die eingetragen Daten des Formulars zwischenspichern, sollte es beim Absenden zum Fehler kommen
    // (fehlerhafte regexprüfung z.b.)
    $titel = isset($_POST["titel"]) ? $_POST["titel"] : "";
    $beschreibung = isset($_POST["beschreibung"]) ? $_POST["beschreibung"] : "";
    $anfangsdatum = isset($_POST["anfangsdatum"]) ? $_POST["anfangsdatum"] : date('Y-m-d');
    $anfangszeit = isset($_POST["anfangszeit"]) ? $_POST["anfangszeit"] : $defaultTime;
    $enddatum = isset($_POST["enddatum"]) ? $_POST["enddatum"] : date('Y-m-d');
    $endzeit = isset($_POST["endzeit"]) ? $_POST["endzeit"] : $ganztagTime;
    $ort = isset($_POST["ort"]) ? $_POST["ort"] : "";
    $kategorie = $db->getAllKategorien(); // Laedt alle vorhanden Kategorien
    $katVal = "";
    $katColor = "#ffffff";
    $tempid = "";
    $group = "";
}
// Formular zum editieren und Neuerstellen eines Termins
$formular = '<form id="kalender" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">
        <input type="text" style="display: none;" id="tempid" name="tempid" value="' . $tempid . '">
        <input type="text" style="display: none;" id="group" name="group" value="' . $group . '">
        <label>Titel:<var>* </var><br/><input size="65px" type="text" name="titel" value="' . $titel . '"><br><br></label>
        <label for="beschreibung">Beschreibung des Termins <br/>
            <textarea id="beschreibung" name=" beschreibung" rows="10" cols="60">' . $beschreibung . '</textarea>
        </label>
        <br>
        <br>
        <label>Anfangsdatum: <input size="50px" type="date" name="anfangsdatum" id="anfangsdatum" value="' . $anfangsdatum . '"></label>
        <label> Anfangszeit: <input size="50px" type="time" name="anfangszeit" id="anfangszeit" value="' . $anfangszeit . '"><br><br></label>
        <label>Enddatum: <input size="50px" type="date" name="enddatum" id="enddatum" value="' . $enddatum . '"></label>
        <label> Endzeit: <input size="50px" type="time" name="endzeit" id="endzeit" value="' . $endzeit . '"><br><br></label>
        <label for="ganztag">ganztaegig: <input size="50px" type="checkbox" name="ganztag" id="ganztag"
                                                checked onclick="setTime(this)"><br><br></label>
        <label>Ort: <input size="50px" type="text" name="ort" value="' . $ort . '"><br><br></label>

        <label>Kategorie: <input type="text" name="kategorie" list="kategorieName" oninput="loadColor(this.value)" value="' . $katVal . '"/>
            <datalist id="kategorieName">
            //Vorhandende Kategorien werden aus der Database geladen' . $kategorie . '
                 
            </datalist>
        </label>

        <label for="farbe"> Farbe:</label>
        <input type="color" id="farbe" name="farbe" value="' . $katColor . '">
        <br/>
        <br/>
        <br/>
        <br/>
        <!--<button type="reset" name="resetbutton" value="Formular zur&uuml;cksetzen">Formular zur&uuml;cksetzen</button>-->
        <button type="submit" name="sendEventBtn" value="absenden">Absenden</button>&nbsp;&nbsp;
        <input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')">
    </form><p></p>';
// Wenn das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $tmpkat = $_POST['kategorie'];

    // Formular auf sinnvolle/korrekte Eingaben ueberpruefen
    $regexTitel = '/^[a-zA-ZüöäÜÖÄß0-9\s\-,.\/\:!\?"]+$/i'; // nur Buchstaben und Zahlen und - , . : ! ? /
    $regexOrt = '/^[a-zA-ZüöäÜÖÄß\s\-]+$/i'; // nur Buchstaben und -
    $regexKategorie = '/^[a-zA-ZüöäÜÖÄß0-9\s\-]+$/i'; // nur Buchstaben, Zahlen und -
    if (empty($titel)) {
        $ausgabe = '<p style="color:#ff0000;">Fehler bei Titel: Bitte einen Titel eingeben.</p>' . $formular;
    } elseif (!preg_match($regexTitel, $_POST['titel'])) {
        $ausgabe = '<p style="color:#ff0000;">Fehler bei Titel: Bitte nur Buchstaben, Zahlen und folgende Sonderzeichen in den Titel eingeben: " - , . : ! ? / "</p>' . $formular;
    } elseif (!empty($ort) && (!preg_match($regexOrt, $_POST['ort']))) {
        $ausgabe = '<p style="color:#ff0000;">Fehler bei Ort: Ort darf nur Buchstaben und "-" enthalten,</p>' . $formular;
    } elseif (strlen($_POST["beschreibung"]) > 500) {
        echo '<p style="color:#ff0000;">Beschreibung ist zu lang. Max. 500 Zeichen.</p>' . $formular;
    }
    elseif (!empty($tmpkat) && (!preg_match($regexKategorie, $_POST['kategorie']))) {
        $ausgabe = '<p style="color:#ff0000;">Kategorie darf nur Buchstaben, Zahlen und "-" enthalten,</p>' . $formular;
    }
    // Wenn Eingaben in Ordnung
    else {
        // Wenn ganztag gesetzt angehakt wurde
        if (isset($_POST['ganztag'])) {
            $tmpGanztag = 1;

        } else {
            $tmpGanztag = 0;
        }

        // Datum und Zeit für Eintrag in Database vorbereiten
        $anfang = $_POST['anfangsdatum'] . " " . $_POST['anfangszeit'];
        $ende = $_POST['enddatum'] . " " . $_POST['endzeit'];
        $factory = new EventFactory();
        $termine = $factory->createEvent($_POST['tempid'], $_POST['titel'], $_POST['beschreibung'], $anfang, $ende, $_POST['ort'], $_POST['kategorie'], $_POST['farbe'], $tmpGanztag);

        // wurde eine neue Kategorie eingegeben?
        $newkatid = null;
        //Wenn die Kategorie noch nicht vorhanden ist und ueberhaupt eine Kategorie eingegeben wurde
        if ($termine[0]->getKategorie() == null && !empty($_POST['kategorie'])) {
          $sql = "INSERT INTO `kategorie` (`name`, `farbe`) VALUES('" . $_POST['kategorie'] . "','" . $_POST['farbe'] . "')";
          $GLOBALS["db"]->insert($sql);
          echo "Neue Kategorie wurde in die Datenbank eingetragen.<br/>";

          $newkatid = $GLOBALS["db"]->getlastId();
        }
        
        foreach ($termine as &$termin) {
          if ($newkatid !== null) {
            $termin->setKategorieid($newkatid);
          }
          // wurde eine neuer Termin eingeben?
          if($_POST['tempid'] == "") {
            $termin->addEvent();
            // wurde ein Termin bearbeitet?
          } else {
              // Ist ist es kein Event ueber mehrere Tage?
              if($_POST['group'] == "") {
                $termin->updateEvent();
                echo $termin->getTitel(). " wurde in Datenbank geaendert.<br/>";
                } else {
                  // sonst
                    $termin->addEvent();
                  }
            }
        }
        if($_POST['group'] !== "") {
          $termine[0]->setGruppe($_POST['group']);
          $termine[0]->deleteEvent();
        }

        $ausgabe .= '<input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')">';
    }
} else {
    $ausgabe = $formular;
}

include("inc/header.inc.php");
echo $ausgabe;
include "inc/footer.inc.php";
?>

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
        var ganztag = new Date();

        //Aktuelles Datum
        var datum = jetzt.getFullYear() + "-" + fuehrendeNull(jetzt.getMonth() + 1) + "-" + fuehrendeNull(jetzt.getDate());

        //aktuelle Zeit
        var uhrzeit = fuehrendeNull(jetzt.getHours()) + ":" + fuehrendeNull(jetzt.getMinutes());

        //setzt die aktuelle Uhrzeit plus eine Stunde
        inEinerStunde.setHours(inEinerStunde.getHours() + 1)
        var uhrzeitPlus = fuehrendeNull(inEinerStunde.getHours()) + ":" + fuehrendeNull(inEinerStunde.getMinutes());

        // Setz ganztag event
        ganztag.setHours(23)
        ganztag.setMinutes(59)
        var timeGanztag = fuehrendeNull(ganztag.getHours()) + ":" + fuehrendeNull(ganztag.getMinutes())

        if (element.checked) {
            document.getElementById("anfangszeit").setAttribute("value", "00:00")
            document.getElementById("endzeit").setAttribute("value", timeGanztag)
            document.getElementById("anfangsdatum").setAttribute("value", datum);
            document.getElementById("enddatum").setAttribute("value", datum);

        } else {
            document.getElementById("anfangszeit").setAttribute("value", uhrzeit);
            document.getElementById("endzeit").setAttribute("value", uhrzeitPlus);
            document.getElementById("anfangsdatum").setAttribute("value", datum);
            document.getElementById("enddatum").setAttribute("value", datum);
        }
    }

    //Farbe wird passen zu ausgewählten Category aktualisiert (mit Ajax)
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
