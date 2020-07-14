<!--
Semester Projekt in dem Modul ISP SS20
TODO Autoren: Christian Till - Matrikelnr. ???  und Felicitas Yeboah - Matrikelnr. 290784
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
    <meta name="author" content="Christian Till - Martrikelnummer: ???">
    <meta name="description" content="ISP - Projekt Terminkalender Gruppe 16 - SS20 - Felicitas Yeboah - 290784 und Christian Till">
    <title>ISP - Projekt Terminkalender Gruppe 16 - SS20 - Felicitas Yeboah - 290784 und Christian Till</title>

</head>
<body>
<main>
    <?php
    //linkDBpdo();
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
    $kalender = new Kalender($monat, $jahr);
    $kalender->show();
    ?>


</main>
</body>
</html>
