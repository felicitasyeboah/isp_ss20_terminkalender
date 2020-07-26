<?php
require_once "dbConf.php";
require_once "index.php";
require_once "Datenbank.php";

$db = new Datenbank(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);
?>
    <form id="kalender" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Titel:<br/><input size="100px" type="text" name="titel"><br><br></label>
        <label for="beschreibung">Beschreibung des Termins <br/>
            <textarea id="beschreibung" name=" beschreibung" rows="10" cols="30"></textarea>
        </label>
        <br>
        <br>
        <label>Anfang:<br/><input size="50px" type="text" name="anfang"><br><br></label>
        <label>Ende:<br/><input size="50px" type="text" name="ende"><br><br></label>
        <label>Ort:<br/><input size="100px" type="text" name="ort"><br><br></label>

        <label for="ganztag">ganztaegig:<br/><input size="50px" type="checkbox" name="ganztag"
                                                    value="1"><br><br></label>

        <label>Kategorie:<br/>
            <input type="text" name="kategorie" list="kategorieName" oninput="myFunction(this.value)"/>

            <datalist id="kategorieName">
                <?php
                //Vorhandende Kategorien werden aus der Datenbank geladen
                $db->getAllKategorien();
                ?>
            </datalist>

        </label>
        <br/>
        <br/>
        <?php

        //TODO Farbe zu ausgewählter Kategorie autoamtsich anzeigen

        echo '<input type="color" id="farbe" name="farbe"
           value="' . $db->getColor(1) . '">
    <label for="farbe">Farbe</label>';
    ?>
        <script>
            function myFunction(val) {
                document.getElementById("farbe").setAttribute("value",val );
            }

        </script>

        <br/>
        <br/>

        <!-- <button type="reset" name="resetbutton" value="Formular zur&uuml;cksetzen">Formular zur&uuml;cksetzen</button>-->
        <button type="submit" name="eintragAbschickenButton" value="absenden">Absenden</button>
    </form>
    <p>&nbsp;</p>
    <a href="index.php">Zurueck zu Startseite</a>

<?php
@$subButton = $_POST['eintragAbschickenButton'];

if (isset($subButton)) {

    if (isset($_POST['ganztag'])) {
        $tmpGanztag = 1;
    } else {
        $tmpGanztag = 0;

    }
    $termin = new Termin($_POST['titel'], $_POST['beschreibung'], $_POST['anfang'], $_POST['ende'], $_POST['ort'], $_POST['kategorie'], $_POST['farbe'], $tmpGanztag);

    //$termin->terminErstellen($_POST['titel'], $_POST['beschreibung'], $_POST['anfang'], $_POST['ende'],  $_POST['ort'],  $_POST['kategorie'], $_POST['farbe'], $termin->getGanztag());
    echo "kategorieID: " . $termin->getKategorieid();

    $db->addEvent($termin);
    echo '<h3> ' . htmlspecialchars($termin->getTitel()) . ' wurde eingetragen!</h3>';
    //$kalender->show();
}
