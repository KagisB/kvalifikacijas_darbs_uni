<?php
session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
include ('Header.php');
?>
<div id="formContainer">
    <form id="lotEdit" method="post" action="../Controllers/AjaxController.php">
        <label for="address">Adrese:</label><br>
        <input type="text" id="address" name="address" minlength="5" maxlength="75" value=""><br>
        <label for="spaceCount">Vietu skaits stāvlaukumā:</label><br>
        <input type="number" id="spaceCount" name="spaceCount" min="1" max="999" size="5" value=""><br>
        <label for="hourlyRate">Stundas maksa par vietu stāvlaukumā:</label><br>
        <input type="number" id="hourlyRate" name="hourlyRate" min="0" size="5" step="0.1" value=""><br><br>
        <input type="submit" value="Rediģēt">
    </form>
</div>

<script>
    $(function() {
        document.getElementById("address").value = <?php echo json_encode($_POST['address']);?>;
        //document.getElementById("username").value = <?php //echo $_POST['username']?>;
        document.getElementById("spaceCount").value = <?php echo json_encode($_POST['spaceCount']);?>;
        document.getElementById("hourlyRate").value = <?php echo json_encode($_POST['hourlyRate']);?>;
        $("#lotEdit").submit(function (e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.
            let address = $("#address").val().toString();
            let spaceCount = parseInt($("#spaceCount").val());
            let hourlyRate = parseFloat($("#hourlyRate").val());
            let lotId = JSON.parse(<?php echo json_encode($_POST['lotId']); ?>)
            //console.log(address+" "+spaceCount+" "+hourlyRate+" "+lotId+" ");
            $.ajax({
                type: "POST",
                url: "../Controllers/AjaxController.php",
                data: {
                    'address': address,
                    'spaceCount': spaceCount,
                    'hourlyRate': hourlyRate,
                    'lotId' : lotId,
                    'action': 'lotEdit',
                },
                //dataType: "json",
                success: function (response) {
                    window.location = 'ParkingLotList.php';
                    //alert("success");
                },
                error: function (response) {
                    //console.log(response);
                    alert("Kaut kas nogāja greizi sistēmā, lūdzu mēģiniet vēlreiz vēlāk!");
                },
            });
        });
    });
</script>