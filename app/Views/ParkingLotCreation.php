<?php
/*
 * Pārbaudīt, vai ir isset() mainīgie no form, un tad izsaukt funkciju no LotController,
 * veiksmīgas izpildes gadījumā nosūtīt uz ParkingLotList.php
 * */
?>
<html lang="lv">
<head>
    <script src="https://code.jquery.com/jquery-3.6.2.js"
            integrity="sha256-pkn2CUZmheSeyssYw3vMp1+xyub4m+e+QK4sQskvuo4="
            crossorigin="anonymous"></script>
    <title>Autostāvlaukuma izveide</title>
</head>
<div>
    <p>Šeit būs input form, lai izveidotu jaunu stāvlaukumu</p>
</div>
<form id="lotCreate" method="post" action="ParkingLotCreation.php">
    <label for="address">Adrese:</label><br>
    <input type="text" id="address" name="address" maxlength="75" minlength="5"><br>
    <label for="spaceCount">Vietu skaits stāvlaukumā:</label><br>
    <input type="number" id="spaceCount" name="spaceCount" min="1" size="5"><br>
    <label for="hourlyRate">Stundas maksa par vietu stāvlaukumā:</label><br>
    <input type="number" id="hourlyRate" name="hourlyRate" min="0" size="5"><br><br>
    <input type="submit" value="Iesniegt">
</form>
<script>
    document.getElementById("lotCreate").addEventListener("submit",checkInput());

    function checkInput(){

    }
</script>
</html>