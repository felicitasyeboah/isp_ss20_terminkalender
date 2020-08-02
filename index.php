    <?php
    require_once("dbConf.php");
    include "Kalender.php";
    include("inc/header.inc.php");
    //require_once("createTable.php");
    echo '<div id="container">';

    //\\ Kalender aufbauen und anzeigen
    $monat = (int)date('m');
    $jahr = (int)date('Y');
    $woche = (int)date('W');
    if (isset($_GET['m'])) {
      $monat = $_GET['m'];
      $jahr = $_GET['j'];
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
    if(isset($_GET['ansicht'])) {
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
      $kalender->setAnsicht("Monat");
      $kalender->showMonth();
    }

    echo '</div>';
    echo '<p></p>';
    
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

    function startFilter(j, m, w) {
      var kat  = document.getElementById("kategorie").value;
      var show = document.querySelector('input[name="ansicht"]:checked').value;
      var params = 'm=' + m + '&j=' + j + '&w=' + w;
      params += (kat  !== '') ? '&kat=' + kat + '' : '';
      params += (show !== '') ? '&ansicht=' + show + '' : '';
      window.location.replace('index.php\?' + params);
     }


    </script>


