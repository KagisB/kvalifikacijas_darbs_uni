<?php
session_start();
include ('Header.php');
/*if(isset($_SESSION['userId'])){
    echo $_SESSION['userId'];
}*/
?>

<div id="mainText">
    <p>Šeit būs saraksts ar stāvlaukumiem, un to adresēm, kā arī vietu skaitu?</p>
</div>
<p id="pageTitle">Autostāvvietu saraksts: </p>
<div id="lotList2">

</div>
<form  id="lotList" action="ParkingSpaceOverview.php" method="get">

</form>
<button id="createLot">
    <a href="ParkingLotCreation.php">Pievienot jaunu stāvlaukumu</a>
</button>
<script>
    $(function(){
        $.ajax({
            type: "POST",
            url:"../Controllers/AjaxController.php",
            async:true,
            data: "action=lotLoad",
            success: function(data){
                let json = JSON.parse(data);
                let select = document.getElementById('lotList');
                select.innerHTML="";
                console.log(typeof json);
                for(let lot of json){
                    //console.log(lot);
                    let lotButton=document.createElement('button');
                    lotButton.value=lot["id"];
                    lotButton.id=lot["id"];
                    lotButton.type="submit";
                    lotButton.name="lotId";
                    lotButton.innerHTML ='Adrese: '+lot["address"]+', kopējais vietu skaits: '+lot['space_count'];//Nosaukumu sarakstā liek mašīnas numuru, var kaut ko citu arī likt
                   /* $('#lotList').on('click','#'+lot["id"]+'',function(

                    ));*/
                    select.appendChild(lotButton);
                }
            }
        });

        $.ajax({
            type: "POST",
            url:"../Controllers/AjaxController.php",
            async:true,
            data: "action=userGet",
            success: function(data){
                //console.log(data);
                let user = JSON.parse(data);
                //console.log(user);
                if(user.status>0){
                    document.getElementById("createLot").style.visibility="visible";
                }
            }
        });
    });
</script>
</html>