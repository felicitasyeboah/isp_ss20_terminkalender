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