<!--
Semester Projekt in dem Modul ISP SS20
TODO Autoren: Christian Till - Matrikelnr. 238872  und Felicitas Yeboah - Matrikelnr. 290784
TODO Abgabedatum  02.08.2020
-->
<?php
require_once("DB_Conf.php");
include "Kalender.php";
//include("inc/header.inc.php");
//require_once("createTable.php");
//include "inc/footer.inc.php";
?>
<!doctype html>
<html lang="de">
<head>
    <link rel="stylesheet" href="css/styles.css">
    <meta name="author" content="Felicitas Yeboah - Martrikelnummer: 290784">
    <meta name="author" content="Christian Till - Martrikelnummer: 238872">
    <meta name="description" content="ISP - Projekt Terminkalender Gruppe 16 - SS20 - Felicitas Yeboah - 290784 und Christian Till - 238872">
    <title>ISP - Projekt Terminkalender Gruppe 16 - SS20 - Felicitas Yeboah - 290784 und Christian Till - 238872</title>

</head>
<body>
<main>
    <?php

    //\\ Verbindung zur Datenbank herstellen
    // TODO $db ist global und war bisher ein PDO Objekt und nicht Objekt der Klasse Datenbank, $db->exec() in z.b.
    // TODO createTable.php lässt sich jetzt nicht mehr auf $db aufrufen, da exec() eine Methode von PDO ist.
    // TODO Sollten überlegen, ob die KLasse Datenbank überhaupt nötig ist oder ob wir nicht die Funktionen der Klasse
    // TODO Datenbank in die Klasse Kalender packen. Kalender verwaltet Termine bzw. Kalender ist ja eigentlich unsere Datenbank.
    // TODO Habe vorerst in createTable ein neues Datenbank-Objekt erzeugt und die Datenbankverbindung mit getDb() zurueckgebenlassen.
    // TODO

    $db = new Datenbank(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);

    //\\ Kalender aufbauen und anzeigen
    $monat = (int)date('m');
    $jahr = (int)date('Y');
    if (isset($_GET['m'])) {
        $monat = $_GET['m'];
        $jahr = $_GET['j'];
        echo "Jahr: " . $jahr;
        echo "Monat: " . $monat;
        if($_GET['m'] == 0) {
            $monat = 12;
            $jahr = $jahr-1;
        }
        if($_GET['m'] == 13) {
            $monat = 1;
            $jahr = $jahr+1;
        }
    }
    $kalender = new Kalender($db, $monat, $jahr);
    $kalender->show();
    ?>

    <script>

      const verzeichnis = "";
      const kalender = verzeichnis + "index.php";
      const XHR = new XMLHttpRequest();

      // Event
      function zeigeEvent(id) {

        XHR.open("GET", kalender +
        "?event&id=" + id, true);
        XHR.send(null);
        XHR.onreadystatechange = ausgabe;
    }

    // Ausgabe
    function ausgabe() {

      if (XHR.readyState == 4 && XHR.status == 200) {

        document.getElementById("event").appendChild(document.createElement("td")).setAttribute("id", "anzeige");
        document.getElementById("anzeige").setAttribute("colspan", "10");

      }
    }
    </script>

<input type="submit" name="addTermin" value="Termin hinzufügen" />


    <form id="kalender" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label>Titel:<br/><input size="100px" type="text" name="titel"><br><br></label>
        <label for="beschreibung">Beschreibung des Termins <br/>
            <textarea id="beschreibung" name=" beschreibung" rows="10" cols="30"></textarea>
        </label>
        <label>Anfang:<br/><input size="50px" type="text" name="anfang"><br><br></label>
        <label>Ende:<br/><input size="50px" type="text" name="ende"><br><br></label>
        <label>ganztaegig:<br/><input size="50px" type="checkbox" name="ganztaegig"><br><br></label>

        <label>Kategorie:<br/></label>
        <label><input type="radio" name="genre" value="0">privat<br/></label>
        <label><input type="radio" name="genre" value="1">schule<br/></label>
        <label><input type="radio" name="genre" value="2">uni<br/></label>
        <label><input type="radio" name="genre" value="3">arbeit<br/></label>
        <label><input type="radio" name="genre" value="4">hobby<br/><br/></label>

        <label>Farbe:<br/></label>
        <label><input type="radio" name="status" value="1">rot</label>
        <label><input type="radio" name="status" value="2">gelb</label>
        <label><input type="radio" name="status" value="3">gruen</label>
        <br/>
        <br/>

        <button type="reset" name="resetbutton" value="Formular zur&uuml;cksetzen">Formular zur&uuml;cksetzen</button>
        <button type="submit" name="eintragAbschickenButton" value="absenden">Absenden</button>
    </form>
    <p>&nbsp;</p>

</main>
</body>
</html>
