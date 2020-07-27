<?php
//include connection
/*if(isset($_POST["class"])){
    $sql_siswa = "SELECT name from student where class like '".$_POST["class"]."' order by name;";
    $result = $conn->query($sql_siswa);
    if ($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<option value=\"".$row["name"]."\">";
        }
    }
}*/
include "Datenbank.php";
include "dbConf.php";

$dbtmp = new Datenbank(MYSQL_DB, MYSQL_HOST, MYSQL_BENUTZER, MYSQL_KENNWORT);

$sql = "SELECT farbe FROM kategorie WHERE id = :id";
$kommando = $dbtmp->getDbCon()->prepare($sql);

$kommando->bindParam(":id", $_GET['color']);
$kommando->execute();
while ($zeile = $kommando->fetch(PDO::FETCH_OBJ)) {
    //echo '<input type="color" id="farbe" name="farbe" value="' . $zeile->farbe . '">
     //   <label for="farbe">Farbe:</label>';
    echo $zeile->farbe;
}
//echo $ergebnis;
