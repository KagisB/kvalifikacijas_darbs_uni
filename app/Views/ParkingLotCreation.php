<?php

session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
include ('Header.php');
?>
<section id="main" class="container-fluid row min-vh-100 min-vw-100">
    <div id="buffer" class="col"></div>
    <div class="col-6">
    <form id="lotCreate" method="post" action="../Controllers/AjaxController.php">
        <label for="address" class="form-label">Adrese:</label><br>
        <input type="text" id="address" name="address" maxlength="75" minlength="5" required class="form-control"><br>
        <label for="spaceCount" class="form-label">Vietu skaits stāvlaukumā:</label><br>
        <input type="number" id="spaceCount" name="spaceCount" min="1" max="999" size="5" required class="form-control"><br>
        <label for="hourlyRate" class="form-label">Stundas maksa par vietu stāvlaukumā:</label><br>
        <input type="number" id="hourlyRate" name="hourlyRate" min="0" size="5" step="0.1" required class="form-control"><br><br>
        <input type="submit" value="Iesniegt" class="btn btn-primary">
    </form>
    </div>
    <div id="buffer2" class="col"></div>
</section>
<script>
    $("#lotCreate").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        let address = $("#address").val().toString();
        let spaceCount = parseInt($("#spaceCount").val());
        let hourlyRate = parseFloat($("#hourlyRate").val());
        console.log(hourlyRate);
        //let ownerId = JSON.parse(<?php //echo json_encode($_SESSION['userId']);?>);
        if(spaceCount> 0 && hourlyRate >= 0){
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
                    console.log(response);
                    alert("response");
                },
            });
        }
        else alert("Stundas maksa vai stāvvietu skaits nav atbilstošs!");
    });
</script>
</html>