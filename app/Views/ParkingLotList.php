<?php
?>
<html lang="lv">
<div>
    <p>Šeit būs saraksts ar stāvlaukumiem, un to adresēm, kā arī vietu skaitu?</p>
</div>
<p>Autostāvvietu saraksts: </p>
<div id="lotList">

</div>
</html>
<script>
    function pageLoad(){
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                let object = JSON.parse(xmlhttp.responseText);

                let select = document.getElementById('lotList');
                select.innerHTML="";
                for(let lot of object){
                    let button = document.createElement('button');
                    button.value=lot["address"];//vērtību piešķir unit id, lai var vieglāk atrast īstos routes
                    button.id=lot["id"];
                    button.innerHTML ='Adrese: '.lot["address"].', kopējais vietu skaits: '.lot['space_count'];//Nosaukumu sarakstā liek mašīnas numuru, var kaut ko citu arī likt
                    select.appendChild(button);
                }
            }
        }
        xmlhttp.open("GET", "../Controllers/mainController.php?carAction=carList", true); // Izveidot router
        //kurš padotu kuru controller metodi izsaukt?
        xmlhttp.send();
    }
</script>
