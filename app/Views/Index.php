<?php
session_start();

include ('Header.php');
$isLoggedIn = false;
if(isset($_SESSION['userId'])){
    $isLoggedIn = true;
}
?>
<div id="Intro" class="container">
    <p>Sveicināti autostāvietu rezervācijas sistēmā.</p>
</div>
<button type="button" id="temp1">
    <h3>Temporary poga priekš citiem page testing1 lotlist</h3>
</button>
<button type="button" id="temp2">
    <h3>Temporary poga priekš citiem page testing2 lotcreate</h3>
</button>
<button type="button" id="temp3">
    <h3>Temporary poga priekš citiem page testing3 spaceoverview</h3>
</button>
<button type="button" id="temp4">
    <h3>Temporary poga priekš citiem page testing4 reservationCreate</h3>
</button>
<script>
    document.getElementById("temp1").addEventListener("click",parkingLotList,false);
    document.getElementById("temp2").addEventListener("click",parkingLotCreate,false);
    document.getElementById("temp3").addEventListener("click",parkingSpaceOverview,false);
    document.getElementById("temp4").addEventListener("click",reservationCreate,false);
function parkingLotList(){
    window.location.href = "ParkingLotList.php";
}
function parkingLotCreate(){
    window.location.href = "ParkingLotCreation.php";
}
function parkingSpaceOverview(){
    window.location.href = "ParkingSpaceOverview.php";
}
function reservationCreate(){
    window.location.href = "ReservationCreate.php";
}
    $(function(){
        /*let userLoggedIn = JSON.parse(<?php //echo json_encode($isLoggedIn); ?>);
        if(userLoggedIn){
            $('#logIn').removeClass(" visible").addClass(" invisible");
            $('#logOut').removeClass(" invisible").addClass(" visible");
            $('#userProfile').removeClass(" invisible").addClass(" visible");
        }*/
        $("#logOut").click(function() {
            $.ajax({
                type: "POST",
                url: "../Controllers/AjaxController.php",
                data: {
                    'action' : 'userLogOut',
                },
                dataType: "json",
                success: function(response)
                {
                    location.reload();
                },
                error: function(response)
                {
                    alert("error")
                },
            });

        });
    });
</script>