<?php
include "EventFactory.php";
$ausgabe = '';

//
$catId = $_GET["id"];
//$factory = new EventFactory();

$kategorie = $db->getKategorie($catId);
$catColor = $kategorie->getFarbe();
$catName = $kategorie->getName();
$regexKategorie = '/^[a-zA-ZüöäÜÖÄß0-9\s\-]+$/i';


$html = '<h2> Kategorie bearbeiten</h2>
<form id="editCat" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . "?id=" . $catId . '" method="post">
<label>Kategoriename: <input type="text" id="kategoriename" name="kategoriename"  onchange="loadCatName(this.value)" value="' . $catName . '">
        </label>
        <label for="farbe"> Kategoriefarbe:</label>
        <input type="color" id="farbe" name="farbe" value="' . $catColor . '">
        <button type="submit" name="changeCatBtn" value="absenden">Kategorie bearbeiten</button>&nbsp;&nbsp;
        <h2> Neue Kategorie anlegen</h2>
        <label>Neue Kategorie: <input type="text" id="neueKat" name="neueKat"  onchange="loadNewCatName(this.value)" value="">
        </label>
        <label for="farbe"> Kategoriefarbe:</label>
        <input type="color" id="neueFarbe" name="neueFarbe" value="#ffffff">
        <button type="submit" name="newCat" value="absenden">Neue Katelgorie anlegen</button>&nbsp;&nbsp;
        <br><br><br>
    

    <input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')"></form>';


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    echo $_POST['kategoriename'];
    if($_POST['neueKat'] === "") {
        if (empty($_POST['kategoriename'])) {
            echo "Kategorie: " . $_POST['kategoriename'];
            $ausgabe = 'Kategorie darf nicht leer sein' . $html;
        } elseif (!preg_match($regexKategorie, $_POST['kategoriename'])) {
            echo "Kategorie: " . $_POST['kategoriename'];
            $ausgabe = 'Kategorie darf nur Buchstaben, Zahlen und "-" enthalten' . $html;
        } else {
            /*if ($this->kategorie !== null) {
            if ($this->kategorie->getId() === null) {
            if ($this->kategorie->getName() !== '') {

                $sql = "INSERT INTO `kategorie` (`name`, `farbe`) VALUES('" . $this->kategorie->getName() . "','" . $this->kategorie->getFarbe() . "')"; //SQL Statement
                $GLOBALS["db"]->insert($sql);*/

            $cat = $GLOBALS["db"]->getKategorie($catId);
            $cat->setFarbe($_POST['farbe']);
            $cat->setName($_POST['kategoriename']);
            $cat->updateCategory();
            $ausgabe .= 'Kategorie wurde ge&auml;ndert. <br><br><input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')"></form>';
        }
    }
    else {
        $newCat = new Category();
        $newCat->addCategory($_POST['neueKat'], $_POST['neueFarbe']);
        $ausgabe .= 'Neue Kategorie wurde angelegt. <br><br><input type="button" name="home" value="zurück zu Startseite" onclick="window.location.replace(\'index.php\')"></form>';
    }

} else {
    $ausgabe = $html;
}
include("inc/header.inc.php");
echo $ausgabe;
include "inc/footer.inc.php";

//$ausgabe .= "Kategorie wurde ge&auml;ndert.";


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

    function loadCatName(name) {
        document.getElementById("kategoriename").setAttribute("value", name);
    }

    function loadNewCatName(name) {
        document.getElementById("neueKat").setAttribute("value", name);
    }
</script>