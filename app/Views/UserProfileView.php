<?php

session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
//echo $_SESSION['logInStatus'];
include ('Header.php');
?>
<section id="main" class="container-fluid min-vh-100 min-vw-100">
    <div class="row">
        <div id="buffer" class="col bg-info"></div>
        <div id="userInfo" class="col-6">
            <div id="username"></div>
            <div id="email"></div>
            <div id="status"></div>
            <div id="editUserInfo">
                <form id="editForm" method="post" action="UserEdit.php" class="d-none"></form>
                <button id="editButton" class="btn btn-info border border-dark border-2">Rediģēt lietotāja datus</button>
            </div>
        </div>
        <div id="buffer2" class="col bg-info bg-gradient"></div>
    </div>
    <div class="row min-vh-100">
        <div id="buffer" class="col bg-info"></div>
        <div id="reservationUsers" class="col-6">
            <p id="pageTitle">Lietotāja rezervācijas saraksts: </p>
            <div id="reservationList">

            </div>
        </div>
        <div id="buffer2" class="col bg-info"></div>
    </div>
</section>
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
                    let reservationCode = document.createTextNode(", Rezervācijas kods: "+reservation.reservation_code);
                    let reservationFrom = document.createTextNode(", Rezervācijas sākums: "+reservation.from);
                    let reservationTill = document.createTextNode(", Rezervācijas beigas: "+reservation.till);

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
            data: {
                'action' : 'userGet',
            },
            success: function(data){
                let user = JSON.parse(data);
                //console.log(user);
                //let editForm = ;
                //createUserEditRedirect(editForm, user);
                let editForm = document.getElementById('editForm');
                createUserEditRedirect(editForm, user);
                let textBox = document.createTextNode("Lietotājvārds: "+user.username);
                document.getElementById('username').appendChild(textBox);
                textBox = document.createTextNode("Epasts: "+user.email);
                document.getElementById('email').appendChild(textBox);
                switch(user.status){
                    case 0:
                        textBox = document.createTextNode("Grupa: Lietotājs");
                        document.getElementById('status').appendChild(textBox);
                        break;
                    case 1:
                        textBox = document.createTextNode("Grupa: Moderators");
                        document.getElementById('status').appendChild(textBox);
                        break;
                    case 2:
                        textBox = document.createTextNode("Grupa: Administrators");
                        document.getElementById('status').appendChild(textBox);
                        break;
                }
            }
        });
        document.getElementById('editButton').addEventListener("click",submitForm,false)
    });
    function submitForm() {
        document.getElementById('editForm').submit();
    }
    function createUserEditRedirect(editForm, user){

        let usernameInput = document.createElement('input');
        usernameInput.name = "username";
        usernameInput.value = user.username;
        usernameInput.type="hidden";

        let emailInput = document.createElement('input');
        emailInput.name="email";
        emailInput.value=user.email;
        emailInput.type="hidden";

        editForm.appendChild(usernameInput);
        editForm.appendChild(emailInput);
    }
    function createSpaceRedirect(reservation){
        let linkForm = document.createElement("form");
        linkForm.method = "POST";
        linkForm.action = "ParkingSpaceReservations.php";
        linkForm.id=reservation.space_id;
        //linkForm.className="d-none";
        let linkInput = document.createElement("input");
        linkInput.type = "hidden";
        linkInput.value = reservation.space_id;
        linkInput.name = "spaceId";
        let linkToSpace = document.createElement("button");
        linkToSpace.type = "submit";
        linkToSpace.textContent = "Aiziet uz stāvvietu";
        linkToSpace.value = "Aiziet uz stāvvietu";
        //Potenciāli ieviest jaunu lapu, kurā konkrētai vietai rādītos rezervāciju pilnais saraksts?
        linkForm.appendChild(linkInput);
        linkForm.appendChild(linkToSpace);
        return linkForm;
    }
</script>
</html>
