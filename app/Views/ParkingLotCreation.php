<?php

session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
include ('Header.php');
?>
<div>
    <p>Šeit būs input form, lai izveidotu jaunu stāvlaukumu</p>
</div>
<form id="lotCreate" method="post" action="../Controllers/AjaxController.php">
    <label for="address">Adrese:</label><br>
    <input type="text" id="address" name="address" maxlength="75" minlength="5"><br>
    <label for="spaceCount">Vietu skaits stāvlaukumā:</label><br>
    <input type="number" id="spaceCount" name="spaceCount" min="1" max="999" size="5"><br>
    <label for="hourlyRate">Stundas maksa par vietu stāvlaukumā:</label><br>
    <input type="number" id="hourlyRate" name="hourlyRate" min="0" size="5" step="0.1"><br><br>
    <input type="submit" value="Iesniegt">
</form>
<script>
    $("#lotCreate").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        let address = $("#address").val().toString();
        let spaceCount = parseInt($("#spaceCount").val());
        let hourlyRate = parseFloat($("#hourlyRate").val());
        //let ownerId = JSON.parse(<?php echo json_encode($_SESSION['userId']);?>);
        $.ajax({
            type: "POST",
            url: "../Controllers/AjaxController.php",
            data: {
                'address': address,
                'spaceCount': spaceCount,
                'hourlyRate': hourlyRate,
                //'owner_id' : ownerId,
                'action' : 'lotCreate',
            },
            dataType: "json",
            success: function(response)
            {
                window.location = 'ParkingLotList.php';
                //alert("success");
            },
            error: function(response)
            {
                //console.log(response);
                alert("error");
            },
        });

    });
</script>
</html>