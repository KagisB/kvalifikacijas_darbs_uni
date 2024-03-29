<?php

session_start();
include ('Header.php');
//echo $_GET['lotId'];
?>
<section id="main" class="container-fluid min-vh-100 min-vw-100">
    <div id="lotInfo" class="container text-dark text-center">
        Lai izvēlētos stāvvietu, kurai veik rezervāciju, nospiediet uz stāvvietas kastes. Tad, nospiežiet pogu rezervēt. Ja kaste ir dzeltenā krāsā, tā pašlaik nav aizņemta.
        Ja kaste ir tumši zilā krāsā, pašlaik šī stāvvieta ir aizņemta, bet iespējams vēlāk tā būs atkal pieejama.
    </div>
    <div id="parkingSpaceDisplay" class="container">

    </div>
</section>
<script>
    $(function(){
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
        $.ajax({
            type : "POST",
            url:"../Controllers/AjaxController.php",
            async:true,
            data: {
                'action' : 'spaceLoad',
                'lotId' : JSON.parse(<?php echo json_encode($_GET['lotId']); ?>),
            },
            success: function(data){
                let json = JSON.parse(data);
                let select = document.getElementById('parkingSpaceDisplay');
                select.innerHTML="";
                let spaceCount = 0;
                let rowCount = 0;
                for(let space of json){
                    //Izveidot div priekš 5 vietu uzglabāšanas, lai visas parking vietas nav vienā rindā saspiestas
                    if(spaceCount % 5 === 0){//Nomainīt skaitli uz to, cik vietas grib vienā rindā
                        rowCount++;
                        let spaceBoxContainer = document.createElement('div');
                        spaceBoxContainer.className ="row justify-content-md-center";
                        spaceBoxContainer.id="spaceRow"+rowCount;
                        select.appendChild(spaceBoxContainer);
                    }

                    let selectBox = document.getElementById('spaceRow'+rowCount);
                    let spaceBox=document.createElement('div');
                    spaceBox.className='col';
                    spaceBox.value=space["number"];
                    spaceBox.id=space["id"];
                    let content = document.createTextNode('Stāvvietas numurs: '+space["number"]);
                    spaceBox.appendChild(content);
                    if(space['reservationStatus']){
                        spaceBox.style.backgroundColor = 'MidnightBlue';
                        spaceBox.style.color = "White";
                    }
                    else spaceBox.style.backgroundColor = 'LemonChiffon';
                    spaceCount++;
                    addRedirectForm(spaceBox);
                    $('#parkingSpaceDisplay').on('click','#'+spaceBox.id,function(){
                        //console.log($('#'+spaceBox.id).attr('id'));
                        if($('#spaceForm'+spaceBox.id).hasClass("d-none")){
                            $('#spaceForm'+spaceBox.id).removeClass("d-none");
                        }
                        else $('#spaceForm'+spaceBox.id).addClass("d-none");
                    });
                    selectBox.appendChild(spaceBox);
                }
            }
        })

        function addRedirectForm(spaceBox){
            let redirectForm = document.createElement('form');
            redirectForm.method="POST";
            redirectForm.action="ParkingSpaceReservations.php";
            redirectForm.id="spaceForm"+spaceBox.id;
            redirectForm.className="d-none";

            let userIdInput = document.createElement('input');
            userIdInput.name = "userId";
            userIdInput.value = JSON.parse(<?php echo json_encode($_SESSION['userId']);?>);
            userIdInput.type="hidden";

            let spaceIdInput = document.createElement('input');
            spaceIdInput.name="spaceId";
            spaceIdInput.value=spaceBox.id;
            spaceIdInput.type="hidden";

            let submitButtonReservation = document.createElement('input');
            submitButtonReservation.type="submit";
            submitButtonReservation.value="Rezervēt";
            submitButtonReservation.className="btn btn-info border border-dark border-2";

            let lotIdInput = document.createElement('input');
            lotIdInput.name="lotId";
            lotIdInput.value= JSON.parse(<?php echo json_encode($_GET['lotId']); ?>);
            lotIdInput.type="hidden";

            let logInReminder = document.createElement('p');
            let logInReminderText = document.createTextNode("Lai rezervētu, jāieiet sistēmā");
            logInReminder.appendChild(logInReminderText);
            logInReminder.style.backgroundColor="LightCoral";

            redirectForm.appendChild(userIdInput);
            redirectForm.appendChild(spaceIdInput);
            redirectForm.appendChild(lotIdInput);
            let logInStatus= JSON.parse(<?php echo json_encode($_SESSION['logInStatus']);?>);
            if(logInStatus){
                redirectForm.appendChild(submitButtonReservation);
            }
            else{
                redirectForm.appendChild(logInReminder);
            }

            spaceBox.appendChild(redirectForm);
        }
    });
</script>
</html>