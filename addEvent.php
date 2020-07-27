<?php
require_once "dbConf.php";
require_once "Datenbank.php";
$db = new Datenbank(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);
?>
    <form id="kalender" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Titel:<br/><input size="65px" type="text" name="titel"><br><br></label>
        <label for="beschreibung">Beschreibung des Termins <br/>
            <textarea id="beschreibung" name=" beschreibung" rows="10" cols="60"></textarea>
        </label>
        <br>
        <br>
        <label>Anfangsdatum: <input size="50px" type="date" name="anfangsdatum" value="<?php echo date('Y-m-d');
?>"></label>
        <label> Anfangszeit: <input size="50px" type="time" name="anfangszeit" value="<?php echo date('H:i');
            ?>"><br><br></label>
        <label>Enddatum: <input size="50px" type="date" name="enddatum" value="<?php echo date('Y-m-d');
            ?>"></label>
        <label> Endzeit: <input size="50px" type="time" name="endzeit" value="<?php echo date('H:i', time()+3600);
            ?>"><br><br></label>
        <label for="ganztag">ganztaegig: <input size="50px" type="checkbox" name="ganztag"
                                                value="1"><br><br></label>
        <label>Ort: <input size="50px" type="text" name="ort"><br><br></label>

        <label>Kategorie: <input type="text" name="kategorie" list="kategorieName" oninput="loadColor(this.value)"/>
            <datalist id="kategorieName">
                <?php
                //Vorhandende Kategorien werden aus der Datenbank geladen
                $db->getAllKategorien();
                ?>
            </datalist>
        </label>

        <!-- //Farbe wird passen zu ausgewählten Kategorie aktualisiert (mit Ajax)-->
        <script>
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
        <label for="farbe"> Farbe:</label>
        <input type="color" id="farbe" name="farbe" value="#ffffff">
        <br/>
        <br/>
        <br/>
        <br/>
        <!--<button type="reset" name="resetbutton" value="Formular zur&uuml;cksetzen">Formular zur&uuml;cksetzen</button>-->
        <button type="submit" name="sendEventBtn" value="absenden">Absenden</button>&nbsp;&nbsp;
        <input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace('index.php')">
    </form>
    <p>&nbsp;</p>

<?php
@$subButton = $_POST['sendEventBtn'];

if (isset($subButton)) {
    if (isset($_POST['ganztag'])) {
        $tmpGanztag = 1;
    } else {
        $tmpGanztag = 0;
    }

    //Datum udn Zeit für Eintrag in Datenbank vorberreiten
    $anfang = $_POST['anfangsdatum'] . " " . $_POST['anfangszeit'] . ":00";
    echo $anfang;
    $ende = $_POST['enddatum'] . " " . $_POST['endzeit'] . ":00";
    echo $ende;
    $termin = new Termin($_POST['titel'], $_POST['beschreibung'], $anfang, $ende, $_POST['ort'], $_POST['kategorie'], $_POST['farbe'], $tmpGanztag);

    //$termin->terminErstellen($_POST['titel'], $_POST['beschreibung'], $_POST['anfang'], $_POST['ende'],  $_POST['ort'],  $_POST['kategorie'], $_POST['farbe'], $termin->getGanztag());
    echo "kategorieID: " . $termin->getKategorieid();

    $db->addEvent($termin);
    echo '<h3> ' . htmlspecialchars($termin->getTitel()) . ' wurde eingetragen!</h3>';
}
