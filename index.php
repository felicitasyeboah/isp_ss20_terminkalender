    <?php
    require_once("dbConf.php");
    include "Kalender.php";
    include("inc/header.inc.php");
    //require_once("createTable.php");
    echo '<div id="container">';

    //\\ Verbindung zur Database herstellen
    // Wird nun in der Datei Database.php erstellt
    //$db = new Database(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);

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
    if (isset($_GET['w'])) {
        $woche = $_GET['w'];
    }
    $kalender = new Kalender($monat, $jahr, $woche);
    if(isset($_GET['kat'])) {
      $kalender->setKatFilter($_GET['kat']);
    }
    //$kalender->showMonth();

    // Für Methode ohne Ajax
    if(isset($_POST['wochenansicht']) || isset($_GET['w'])) {
        $kalender->showWeek();
    }
    elseif(isset($_POST['tagesansicht'])) {
        $kalender->showDay();
    }
    else {
        $kalender->showMonth();

    }
    echo '</div>';
    echo '<p></p>';
    // Laedt Seite editEvent.php -->
    
    // Buttons zum Wechseln zwischen Monats-, Wochen- und Tagesansicht für Methode ohne Ajax.
   echo '<form method="post" action="'. $_SERVER['PHP_SELF'].'">
        <input type="submit" name="wochenansicht" value="Wochenansicht">
    </form>';
    echo '<form method="post" action="'. $_SERVER['PHP_SELF'].'">
        <input type="submit" name="tagesansicht" value="Tagesansicht">
    </form>';
    echo '<form method="post" action="'. $_SERVER['PHP_SELF'].'">
        <input type="submit" name="monatsansicht" value="Monatsansicht">
    </form>';

    // Buttons zum Wechseln zwischen Monats-, Wochen- und Tagesansicht für Methode mit Ajax.
    /*echo '<input type="button" name="showWeek" value="Wochenansicht" onclick="loadWeek(' . $woche . ', '.$monat .' , '.$jahr .')">';
    echo '<input type="button" name="showWeek" value="Tagesansicht" onclick="loadDay(' . $woche . ', '.$monat .' , '.$jahr .')">';
    echo '<input type="button" name="showWeek" value="Monatsansicht" onclick="loadMonth(' . $woche . ', '.$monat .' , '.$jahr .')">';*/


    include "inc/footer.inc.php";
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
            xhttp.open("POST", "ajaxCalViews.php", true);
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

            xhttp.open("POST", "ajaxCalViews.php", true);
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
            xhttp.open("POST", "ajaxCalViews.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
            xhttp.send("woche=" +w+"&monat="+m+"&jahr="+j+"&v="+validate+"");
        }
    </script>

    <script>

      const verzeichnis = "";
      const event = verzeichnis + "Event.php";
      const kalender = verzeichnis + "Kalender.php";
      const XHR = new XMLHttpRequest();

      // Event
      function zeigeEvent(id) {
        XHR.open("GET", event +
        "?details&id=" + id, true);
        XHR.send(null);
        XHR.onload = ausgabe;
        XHR.onerror = function () {
         console.log('Error: ' + xhr.status); // Ein Fehler ist aufgetreten
      }
    }

    // Ausgabe
    function ausgabe() {
      
      if (XHR.readyState == 4 && XHR.status == 200) {
        document.getElementById("event").innerHTML = this.responseText;
      }
    }

    function zeigeFilter() {
      var element = document.getElementById("filter");
      element.classList.toggle("invisible");
    }

    function bearbeiteKategorie() {
      var kat = document.getElementById("kategorie").value;
      window.location.replace('editCategory.php\?id=' + kat + '') ;
    }

    function startFilter(j, m) {
      var kat  = document.getElementById("kategorie").value;
      //var show = document.getElementById("ansicht").value;
      window.location.replace('index.php\?m=' + m + '&j=' + j + '&kat=' + kat + '');
      //$ausgabe .= '<caption><a href="index.php?m=' . ($this->monat - 1) . '&j=' . $this->jahr . '">vorheriger</a>&nbsp;' . $this->infoDatum['month'] . ' ' . $this->jahr . '&nbsp;<a href="index.php?m=' . ($this->monat + 1) . '&j=' . $this->jahr . '">n&auml;chster</a>';

    }

    </script>


