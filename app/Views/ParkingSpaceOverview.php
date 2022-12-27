<?php
session_start();
include ('Header.php');
//echo $_GET['lotId'];
?>

<div id="lotInfo">
    <p>Šeit būs saraksts ar stāvvietām konkrētā stāvlaukumā. Gan jau augšā jāpatur stāvlaukuma adrese/nosaukums,
        tad arī visas stāvvietas, gan jau flexbox izmantojot, jāattēlo, un jāatver pop up info log, kad uzspiež uz
        kādu no vietām. Norādījumi, ko nozīmē dažādās krāsas utt.</p>
</div>
<div id="spaceDisplayContainer" class="container-fluid">
    <div id="parkingSpaceDisplay" class="container">

    </div>
</div>
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

                    if(space['reservation_status']){
                        spaceBox.style.backgroundColor = 'LightCoral';
                    }
                    else spaceBox.style.backgroundColor = 'LawnGreen';
                    spaceCount++;
                    addRedirectForm(spaceBox);
                    $('#parkingSpaceDisplay').on('click','#'+spaceBox.id,function(){
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
            redirectForm.method="GET";
            redirectForm.action="ReservationCreate.php";
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

            let lotIdInput = document.createElement('input');
            lotIdInput.name="lotId";
            lotIdInput.value= JSON.parse(<?php echo json_encode($_GET['lotId']); ?>);
            lotIdInput.type="hidden";

            let logInReminder = document.createElement('p');
            let logInReminderText = document.createTextNode("Lai rezervētu, jāielogojas");
            logInReminder.appendChild(logInReminderText);
            logInReminder.style.backgroundColor="LightCoral";

            redirectForm.appendChild(userIdInput);
            redirectForm.appendChild(spaceIdInput);
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