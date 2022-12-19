<?php
session_start();
?>
<html lang="lv">
<head>
    <script src="https://code.jquery.com/jquery-3.6.2.js"
    integrity="sha256-pkn2CUZmheSeyssYw3vMp1+xyub4m+e+QK4sQskvuo4="
    crossorigin="anonymous"></script>
    <title>Autostāvlaukumi</title>
</head>
<div id="mainText">
    <p>Šeit būs saraksts ar stāvlaukumiem, un to adresēm, kā arī vietu skaitu?</p>
</div>
<p id="pageTitle">Autostāvvietu saraksts: </p>
<div id="lotList">

</div>
<button id="createLot" style="display:none">
    <p>Pievienot jaunu stāvlaukumu</p>
</button>
<script>
    $(function(){
        $.ajax({
            url:"../Controllers/AjaxController.php",
            async:true,
            dataType:JSON,
            data: "action=lotLoad",
            success: function(data){
                let json = JSON.parse(data);
                let select = document.getElementById('lotList');
                select.innerHTML="";
                for(let lot of json){
                    let lotButton=$.createElement('button');
                    lotButton.value=lot["address"];//vērtību piešķir unit id, lai var vieglāk atrast īstos routes
                    lotButton.id=lot["id"];
                    lotButton.innerHTML ='Adrese: '.lot["address"].', kopējais vietu skaits: '.lot['space_count'];//Nosaukumu sarakstā liek mašīnas numuru, var kaut ko citu arī likt
                    select.appendChild(lotButton);
                }
            }
        });
        $.ajax({
            url:"../Controllers/AjaxController.php",
            async:true,
            dataType:JSON,
            data: "action=userGet",
            success: function(data){
                let json = JSON.parse(data);
                let lotCreateButton = $.('$createLot');
                for(let user of json){
                    if(user['status']>0){
                        lotCreateButton.style.display = 'block';
                    }
                }
            }
        });
    });
</script>
</html>