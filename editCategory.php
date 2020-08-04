<?php
/**
 * Formular zum Aendern, Loeschen und Erstellen einer Kategorie
 */
include "EventFactory.php";
$ausgabe = '';
$html = '';

// wurde eine Kategorie zum bearbeiten uebergeben?
if (isset($_GET["id"])) {
  $catId = $_GET["id"];
  $kategorie = $db->getKategorie($catId);
  $catColor = $kategorie->getFarbe();
  $catName = $kategorie->getName();
  $regexKategorie = '/^[a-zA-ZüöäÜÖÄß0-9\s\-]+$/i'; // nur Buchstaben, Zahlen und "-

  $html .= '<h2> Kategorie bearbeiten</h2>
  <form id="editCat" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . "?id=" . $catId . '" method="post">
  <label>Kategoriename: <input type="text" id="kategoriename" name="kategoriename"  onchange="loadCatName(this.value)" value="' . $catName . '">
  </label>
  <label for="farbe"> Kategoriefarbe:</label>
  <input type="color" id="farbe" name="farbe" value="' . $catColor . '">
  <button type="submit" name="changeCatBtn" value="absenden">Kategorie bearbeiten</button>&nbsp;&nbsp;
  <div class="eventLink ico_del" onclick="window.location.replace(\'Category.php\?deletecat&id=' . $catId . '\')"></div>
  <h2> Neue Kategorie anlegen</h2>
  <label>Neue Kategorie: <input type="text" id="neueKat" name="neueKat"  onchange="loadNewCatName(this.value)" value="">
  </label>
  <label for="farbe"> Kategoriefarbe:</label>
  <input type="color" id="neueFarbe" name="neueFarbe" value="#ffffff">
  <button type="submit" name="newCat" value="absenden">Neue Katelgorie anlegen</button>&nbsp;&nbsp;';
} else {
  $html .= '<div>Bitte geben Sie eine Kategorie an!</div>';
}
$html .= '<br><br><br><input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')"></form>';


@$subChangeCat = $_POST['changeCatBtn'];
@$subNewCat = $_POST['newCat'];

    // Wenn eine bestehende Kategorie geaendert werden soll
    if(isset($subChangeCat)) {
        if (empty($_POST['kategoriename'])) {
            $ausgabe = '<p style="color:#ff0000">Kategorie darf nicht leer sein. Zum L&ouml;schen einer Kategorie auf das M&uumllleimer-Symbol klicken.</p>' . $html;
        } elseif (!preg_match($regexKategorie, $_POST['kategoriename'])) {
            $ausgabe = '<p style="color:#ff0000">Kategorie darf nur Buchstaben, Zahlen und "-" enthalten</p>' . $html;
        } else {
            $cat = $GLOBALS["db"]->getKategorie($catId);
            $cat->setFarbe($_POST['farbe']);
            $cat->setName($_POST['kategoriename']);
            $cat->updateCategory();
            $ausgabe .= 'Kategorie wurde ge&auml;ndert. <br><br><input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')"></form>';
        }
    }
    // Wenn eine neue Kategorie angelegt werden soll
     elseif(isset($subNewCat)) {
         if (empty($_POST['neueKat'])) {
             $ausgabe = '<p style="color:#ff0000">Kategorie darf nicht leer sein. Um eine Kategorie anzulegen, bitte einen Namen eingeben.</p>' . $html;
         } elseif (!preg_match($regexKategorie, $_POST['neueKat'])) {
             $ausgabe = '<p style="color:#ff0000">Kategorie darf nur Buchstaben, Zahlen und "-" enthalten</p>' . $html;
         } else {
             $newCat = new Category();
             $newCat->addCategory($_POST['neueKat'], $_POST['neueFarbe']);
             $ausgabe .= '<br><br><input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')"></form>';
         }
     }

 else {
    $ausgabe = $html;
}
include("inc/header.inc.php");
echo $ausgabe;
include "inc/footer.inc.php";

?>
<script>
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
    // Setzt Value der ausgewählten Kategorie
    function loadCatName(name) {
        document.getElementById("kategoriename").setAttribute("value", name);
    }
    // Setzt Wert der der neuen Kategorie
    function loadNewCatName(name) {
        document.getElementById("neueKat").setAttribute("value", name);
    }
</script>