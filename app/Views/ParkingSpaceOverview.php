<?php
session_start();
?>
<html lang="lv">
<head>
    <script src="https://code.jquery.com/jquery-3.6.2.js"
            integrity="sha256-pkn2CUZmheSeyssYw3vMp1+xyub4m+e+QK4sQskvuo4="
            crossorigin="anonymous"></script>
    <title>Autostāvvietas stāvlaukumā</title>
</head>
<div id="lotInfo">
    <p>Šeit būs saraksts ar stāvvietām konkrētā stāvlaukumā. Gan jau augšā jāpatur stāvlaukuma adrese/nosaukums,
        tad arī visas stāvvietas, gan jau flexbox izmantojot, jāattēlo, un jāatver pop up info log, kad uzspiež uz
        kādu no vietām. Norādījumi, ko nozīmē dažādās krāsas utt.</p>
</div>
<div id="parkingSpaceDisplay">
    <p>Šeit būs katra vieta attēlota kā kaste, ar numuru, un krāsu atkarībā no tā, ir aizņemts, vai nē.</p>
    <!---Paraugs kā veidot div--->
    <div id="parkingSpaceBox">
        <p>Numurs</p>
        <p>kaste nokrāsota zaļa, ja brīvs, sarkans, ja aizņemts, dzeltens, ja nākamo 4? stundu laikā ir rezervācija?</p>
        <p>Var nospiest uz kastes, un atvērsies Popup ar formu, kura parāda vairāk info??</p>
    </div>
</div>
<script>
    $(function(){
        $.ajax({
            url:"../Controllers/AjaxController.php",
            async:true,
            dataType:JSON,
            data: "action=spaceListLoad,lotId=<?php echo $_POST['lotId'] ?>",
            success: function(data){
                let json = JSON.parse(data);
                let select = document.getElementById('parkingSpaceDisplay');
                select.innerHTML="";
                for(let space of json){
                    let spaceBox=$.createElement('div');
                    spaceBox.setAttribute("class",'');
                    spaceBox.value=space["number"];//vērtību piešķir unit id, lai var vieglāk atrast īstos routes
                    spaceBox.id=space["id"];
                    spaceBox.innerHTML ='Stāvvietas numurs: '.space["number"];//Nosaukumu sarakstā liek mašīnas numuru, var kaut ko citu arī likt
                    if(space['reservation_status']){
                        spaceBox.style.backgroundColor = 'green';
                    }
                    else spaceBox.style.backgroundColor = 'red';
                    select.appendChild(spaceBox);
                }
            }
        })
        $.ajax({
            url:"../Controllers/AjaxController.php",
            async:true,
            dataType:JSON,
            data: "action=userGet",
            success: function(data){
                let json = JSON.parse(data);
                let lotCreateButton = $.('$createLot');
                for(let user of json){
                    if(user['status'] !== null){//nomainīt uz to, ka logged in user var izveidot rezervāciju
                        lotCreateButton.style.display = 'block';
                    }
                }
            }
        });
    });
</script>
</html>