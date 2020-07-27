<!--
Semester Projekt in dem Modul ISP SS20
TODO Autoren: Christian Till - Matrikelnr. 238872  und Felicitas Yeboah - Matrikelnr. 290784
TODO Abgabedatum  02.08.2020
-->
<?php
require_once("dbConf.php");
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
    echo '<div id="container">';
    //\\ Verbindung zur Datenbank herstellen
    // Wird nun in der Datei Datenbank.php erstellt
    //$db = new Datenbank(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);

    //\\ Kalender aufbauen und anzeigen
    $monat = (int)date('m');
    $jahr = (int)date('Y');
    $woche = (int)date('W');
    echo $woche;
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
    $kalender = new Kalender($monat, $jahr, $woche);
    $kalender->showMonth();
    // Für Methode ohne Ajax
    /*if(isset($_POST['wochenansicht'])) {
        $kalender->showWeek();
    }
    elseif(isset($_POST['tagesansicht'])) {
        $kalender->showDay();
    }
    else {
        $kalender->showMonth();

    }*/
    echo '</div>';
    echo '<p></p>';
   //$kalender->showWeek();
    // Laedt Seite addEvent.php -->
    echo '<input type="button" name="addEvent" value="Termin hinzufügen" onclick="window.location.replace(\'addEvent.php\')">';

    // Buttons zum Wechseln zwischen Monats-, Wochen- und Tagesansicht für Methode ohne Ajax.
   /* echo '<form method="post" action="'. $_SERVER['PHP_SELF'].'">
        <input type="submit" name="wochenansicht" value="Wochenansicht">
    </form>';
    echo '<form method="post" action="'. $_SERVER['PHP_SELF'].'">
        <input type="submit" name="tagesansicht" value="Tagesansicht">
    </form>';
    echo '<form method="post" action="'. $_SERVER['PHP_SELF'].'">
        <input type="submit" name="monatsansicht" value="Monatsansicht">
    </form>';*/

    // Buttons zum Wechseln zwischen Monats-, Wochen- und Tagesansicht für Methode mit Ajax.
    echo '<input type="button" name="showWeek" value="Wochenansicht" onclick="loadWeek(' . $woche . ', '.$monat .' , '.$jahr .')">';
    echo '<input type="button" name="showWeek" value="Tagesansicht" onclick="loadDay(' . $woche . ', '.$monat .' , '.$jahr .')">';
    echo '<input type="button" name="showWeek" value="Monatsansicht" onclick="loadMonth(' . $woche . ', '.$monat .' , '.$jahr .')">';
    ?>
    <!-- Javascript Funktionen zum laden der einzelnen Ansichten des Kalenders mittels Ajax -->
    <script>
        function loadWeek(w, m, j) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("container").innerHTML = this.responseText;
                }
            };
            var validate = "week";
            xhttp.open("POST", "ajaxShowWeek.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
            xhttp.send("woche=" +w+"&monat="+m+"&jahr="+j+"&v="+validate+"");
        }
        function loadDay(w, m, j) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("container").innerHTML = this.responseText;
                }
            };
            var validate = "day";

            xhttp.open("POST", "ajaxShowWeek.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
            xhttp.send("woche=" +w+"&monat="+m+"&jahr="+j+"&v="+validate+"");
        }
        function loadMonth(w, m, j) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("container").innerHTML = this.responseText;
                }
            };
            var validate = "month";
            xhttp.open("POST", "ajaxShowWeek.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
            xhttp.send("woche=" +w+"&monat="+m+"&jahr="+j+"&v="+validate+"");
        }
    </script>

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


</main>
</body>
</html>
