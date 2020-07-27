<?php
include "Kalender.php";
$k = new Kalender( $_POST['monat'], $_POST['jahr'], $_POST['woche']);
if($_POST['v'] == "week") {
    $k->showWeek();
}
elseif($_POST['v'] == "day") {
    $k->showDay();
}
elseif($_POST['v'] == "month") {
    $k->showMonth();
}
