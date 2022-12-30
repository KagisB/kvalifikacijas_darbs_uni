<?php
session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
include ('Header.php');
?>
<div id="mainText">
    <p>Lietotāja info, varbūt pievienot iespēju izdzēst kontu vai rediģēt?</p>
</div>
<div id="userInfo">
    <div id="userName">Lietotājs: </div>
    <div id="email">Epasts: </div>
    <div id="status">Grupa: </div>
</div>
<p id="pageTitle">Lietotāja rezervācijas saraksts: </p>
<div id="reservationList">

</div>
<script>
    $(function(){
        let userId = JSON.parse(<?php echo json_encode($_SESSION['userId'])?>);
        $.ajax({
            type: "POST",
            url: "../Controllers/AjaxController.php",
            async:true,
            //dataType:JSON,
            data: {
                'action' : 'reservationLoadUser',
                'userId' : userId,
            },
            success: function(data){
                let json = JSON.parse(data);
                let select = document.getElementById('reservationList');
                select.innerHTML="";
                for(let reservation of json){
                    let reservationBox = document.createElement('div');
                    reservationBox.id = reservation.id;
                    let reservationSpace = document.createTextNode("Vietas numurs: "+reservation.space_id);
                    let reservationCode = document.createTextNode("Rezervācijas kods: "+reservation.reservation_code);
                    let reservationFrom = document.createTextNode("Rezervācijas sākums: "+reservation.from);
                    let reservationTill = document.createTextNode("Rezervācijas beigas: "+reservation.till);

                    //let spaceOverviewBox = createSpaceRedirect(reservation);

                    reservationBox.appendChild(reservationSpace);
                    reservationBox.appendChild(reservationCode);
                    reservationBox.appendChild(reservationFrom);
                    reservationBox.appendChild(reservationTill);
                    //reservationBox.appendChild(spaceOverviewBox);
                    select.appendChild(reservationBox);
                }
            }
        });
        $.ajax({
            type: "POST",
            url:"../Controllers/AjaxController.php",
            async:true,
            //dataType:JSON,
            data: "action=userGet",
            success: function(data){
                let user = JSON.parse(data);
                $('#userName').innerHTML += user.username;
                $('#email').innerHTML += user.email;
                switch(user.status){
                    case 0:
                        $('#status').innerHTML += "Lietotājs";
                        break;
                    case 1:
                        $('#status').innerHTML += "Moderators";
                        break;
                    case 2:
                        $('#status').innerHTML += "Administrators";
                        break;
                }
            }
        });
    });
    function createSpaceRedirect(reservation){
        let linkForm = document.createElement("form");
        linkForm.method = "GET";
        linkForm.action = "ParkingSpaceReservations.php";
        linkForm.id=reservation.space_id;
        //linkForm.className="d-none";
        let linkInput = document.createElement("input");
        linkInput.type = "hidden";
        linkInput.value = reservation.space_id;
        linkInput.name = "spaceId";
        let linkToSpace = document.createElement("button");
        linkToSpace.type = "submit";
        linkToSpace.value = "Aiziet uz stāvvietu";
        //Potenciāli ieviest jaunu lapu, kurā konkrētai vietai rādītos rezervāciju pilnais saraksts?
        linkForm.appendChild(linkInput);
        linkForm.appendChild(linkToSpace);
        return linkForm;
    }
</script>
</html>
