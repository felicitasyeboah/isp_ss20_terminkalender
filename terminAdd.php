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

        <label for="ganztag">ganztaegig:<br/><input size="50px" type="checkbox" name="ganztag" value="1"><br><br></label>

        <label>Kategorie:<br/>
            <select name="kategorie" size="1">
                <option value="1">privat</option>
                <option value="2">Uni</option>
                <option value="3">Arbeit</option>
                <option value="4">Hobby</option>
                <option value="5">Kat 5</option>
                <option value="6">Kat 6</option>
                <option value="7">Kat 7</option>


            </select>
        </label>

        <br/>
        <br/>
        <input type="color" id="farbe" name="farbe"
               value="#ff0000">
        <label for="farbe">Farbe</label>
        <br/>
        <br/>

        <!-- <button type="reset" name="resetbutton" value="Formular zur&uuml;cksetzen">Formular zur&uuml;cksetzen</button>-->
        <button type="submit" name="eintragAbschickenButton" value="absenden">Absenden</button>
    </form>
    <p>&nbsp;</p>

    <?php
    require_once "index.php";
    @$subButton = $_POST['eintragAbschickenButton'];

    if (isset($subButton)) {
        $termin = new Termin();
        $termin->terminErstellen($_POST['titel'], $_POST['beschreibung'], $_POST['anfang'], $_POST['ende'],  $_POST['ort'], $_POST['ganztag'], $_POST['kategorie'], $_POST['farbe']);
        $db->addEvent($termin);
        echo '<h3> ' . htmlspecialchars($termin->getTitel()) . ' wurde eingetragen!</h3>';
        //$kalender->show();
    }