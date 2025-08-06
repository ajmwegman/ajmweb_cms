<?php
// add.php - Formulier voor het toevoegen van een blogpost
?>
<form method="post" action="../bin/add.php">
    <label>Titel: <input type="text" name="title"></label><br>
    <label>Inhoud: <textarea name="content"></textarea></label><br>
    <label>Status: 
        <select name="status">
            <option value="concept">Concept</option>
            <option value="gepubliceerd">Gepubliceerd</option>
        </select>
    </label><br>
    <label>Publicatiedatum: <input type="date" name="publication_date"></label><br>
    <button type="submit">Toevoegen</button>
</form>
