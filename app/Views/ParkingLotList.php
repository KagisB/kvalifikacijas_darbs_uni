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
<div id="lotList">

</div>
<button id="createLot" style="visibility: hidden">
    <a href="ParkingLotCreation.php">Pievienot jaunu stāvlaukumu</a>
</button>
<script>
    $(function(){
        let userStatus=-1;
        $.ajax({
            type: "POST",
            url:"../Controllers/AjaxController.php",
            async:true,
            data: "action=userGet",
            success: function(data){
                //console.log(data);
                let user = JSON.parse(data);
                //console.log(user);
                userStatus = user.status;
                if(user.status>0){
                    document.getElementById("createLot").style.visibility="visible";
                }
            }
        });
        $.ajax({
            type: "POST",
            url:"../Controllers/AjaxController.php",
            async:true,
            data: "action=lotLoad",
            success: function(data){
                let json = JSON.parse(data);
                let select = document.getElementById('lotList');
                //console.log(typeof json);
                for(let lot of json){
                    console.log(lot);
                    let lotBox=document.createElement('div');
                    lotBox.id="lotBox"+lot["id"];
                    let lotInfo = document.createTextNode('Adrese: '+lot["address"]+', kopējais vietu skaits: '+lot['space_count']+ ', Stundas maksa: '+lot["hourly_rate"]);
                    lotBox.appendChild(lotInfo);
                    viewForm(lotBox,lot);
                    editForm(lotBox,lot);
                    removeButton(lotBox,lot);

                    select.appendChild(lotBox);
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
        function viewForm(lotBox,lot){
                let viewForm = document.createElement("form");
                viewForm.method ="get";
                viewForm.action = "ParkingSpaceOverview.php";
                viewForm.id="viewForm"+lot.id;

                let lotIdInput = document.createElement('input');
                lotIdInput.name="lotId";
                lotIdInput.value= lot.id;
                lotIdInput.type="hidden";

                let viewButton=document.createElement('input');
                viewButton.type = "submit";
                viewButton.value= "Apskatīt stāvvietas"

                viewForm.appendChild(lotIdInput);
                viewForm.appendChild(viewButton);
                lotBox.appendChild(viewForm);
        }
        function editForm(lotBox,lot){
            let userId = JSON.parse(<?php echo json_encode($_SESSION['userId'])?>);
            if(userId === lot.owner_id || userStatus === 2){
                let editForm = document.createElement("form");
                editForm.method ="POST";
                editForm.action = "ParkingLotEdit.php";
                editForm.id="editForm"+lot.id;

                let lotIdInput = document.createElement('input');
                lotIdInput.name="lotId";
                lotIdInput.value= lot.id;
                lotIdInput.type="hidden";
                let lotAddressInput = document.createElement('input');
                lotAddressInput.name="address";
                lotAddressInput.value= lot.address;
                lotAddressInput.type="hidden";
                let lotSpacesInput = document.createElement('input');
                lotSpacesInput.name="spaceCount";
                lotSpacesInput.value= lot.space_count;
                lotSpacesInput.type="hidden";
                let lotRateInput = document.createElement('input');
                lotRateInput.name="hourlyRate";
                lotRateInput.value= lot.hourly_rate;
                lotRateInput.type="hidden";

                let editButton=document.createElement('input');
                editButton.type = "submit";
                editButton.value= "Rediģēt datus"

                editForm.appendChild(lotIdInput);
                editForm.appendChild(lotAddressInput);
                editForm.appendChild(lotSpacesInput);
                editForm.appendChild(lotRateInput);
                editForm.appendChild(editButton);
                lotBox.appendChild(editForm);
            }
        }
        function removeButton(lotBox,lot){
            let userId = JSON.parse(<?php echo json_encode($_SESSION['userId'])?>);
            if(userId === lot.owner_id || userStatus === 2){
                let removeButton=document.createElement('button');
                removeButton.id = "removeButton"+lot.id;
                removeButton.value= "Izdzēst stāvlaukumu";
                let removeText = document.createTextNode('Izdzēst stāvlaukumu')
                removeButton.appendChild(removeText);
                $('#lotList').on('click','#removeButton'+lot.id,function(){
                    //Pievienot jautājumu vai tiešām izdzēst
                    $.ajax({
                        type: "POST",
                        url:"../Controllers/AjaxController.php",
                        async:true,
                        data: {
                            'lotId' : lot.id,
                            'action': "lotRemove",
                        },
                        success: function(data){
                            location.reload();
                        }
                    });
                });
                lotBox.appendChild(removeButton);
            }
        }
    });
</script>
</html>