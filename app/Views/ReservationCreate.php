<?php
session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
//echo $_POST['lotId'];
include ('Header.php');
/*
 * Izveidot input, lai ņem visas rezervācijas mēneša laikā, un neļauj ievadīt laikus, kas neiekļaujas brīvajos laikos.
 * Vajadzētu limitēt laikus ,tas ir, intervāli būtu ik pa pusstundai,stundai utt. Jo citādāk nevarēs izmantot plugins/citas lietas.
 * https://github.com/jannicz/appointment-picker#readme
 * */
?>
<div>
    <p>Šeit varēs izveidot rezervāciju</p>
</div>
<div id="userReservationList">
    <form id="reservationCreate" method="post" action="../Controllers/AjaxController.php">
        <label for="from">Rezervācijas sākums:</label><br>
        <input type="datetime-local" id="from" name="from" min="" max=""><br>
        <label for="till">Rezervācijas beigas:</label><br>
        <input type="datetime-local" id="till" name="till" min="" max=""><br><br>
        <input type="submit" value="Rezervēt">
    </form>
</div>
<script>
    //document.getElementById("from").addEventListener("input",changeMaxMinDate,false);
    $(function() {
        if(document.getElementById("from").value===""){
            setMaxMinDate();
        }
        document.getElementById("from").addEventListener("input",changeMaxMinDate,false);
        $("#reservationCreate").submit(function (e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.
            let fromValue = $("#from").val().toString();
            let tillValue = $("#till").val().toString();
            //console.log(fromValue + "  " + tillValue);
            let from = returnDateString(new Date(fromValue));
            let till = returnDateString(new Date(tillValue));
            let spaceId = JSON.parse(<?php echo json_encode($_POST['spaceId']);?>);
            let userId = JSON.parse(<?php echo json_encode($_POST['userId']);?>);
            //console.log(from+","+till+","+spaceId+","+userId)
            $.ajax({
                type: "POST",
                url: "../Controllers/AjaxController.php",
                data: {
                    'from': from,
                    'till': till,
                    'spaceId': spaceId,
                    'userId': userId,
                    'action': 'reservationCreate',
                },
                dataType: "json",
                success: function (response) {
                    //console.log(response);
                    let lotId=JSON.parse(<?php echo json_encode($_POST['lotId']); ?>);
                    window.location = 'ParkingSpaceOverview.php?lotId='+lotId;
                    //alert("success");
                },
                error: function (response) {
                    //console.log(response);
                    alert("error");
                },
            });

        });
    });
    function setMaxMinDate(){
        let today = new Date();
        let todayString = returnDateString(today);
        let till = new Date();
        till.setMonth(till.getMonth()+1);
        let tillString = returnDateString(till);
        document.getElementById("from").setAttribute("min", todayString);
        document.getElementById("from").setAttribute("max", tillString);
        document.getElementById("till").setAttribute("min", todayString);
        document.getElementById("till").setAttribute("max", tillString);
    }
    function changeMaxMinDate(){
        /*
        Potenciāla funkcija, lai dinamiski atjaunotu atļauto maksimālo vērtību rezervācijas gala datumam,
        ja rezervāciju ļauj veikt vairāk kā mēnesi uz priekšu
         */
        let minDate = new Date(document.getElementById("from").value);
        let minDateString = returnDateString(minDate);
        document.getElementById("till").setAttribute("min", minDateString);
    }

    function returnDateString(currentDate){
        let d=currentDate.getDate();
        let m=currentDate.getMonth()+1;//js shenanigans
        let y=currentDate.getFullYear();
        let h=currentDate.getHours();
        let min=currentDate.getMinutes();
        if(d<10){
            d= '0'+d;
        }
        if(m<10){
            m = '0'+m;
        }
        if(min<10){
            min = '0'+min;
        }
        if(h<10){
            h = '0'+h;
        }
        return y+'-'+m+'-'+d+'T'+h+':'+min;
    }
</script>
</html>
