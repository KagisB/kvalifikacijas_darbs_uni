<?php
session_start();
include ('Header.php');
/*if(!isset($_SESSION['userId'])){
    $_SESSION['userId']=-1;
}*/
?>
<section id="main" class="container-fluid min-vh-100 min-vw-100">
    <div id="row1" class="row min-vw-100">
    <div id="buffer1" class="col bg-info"></div>
    <div id="mainText" class="col-6 text-dark text-center">
        Lai izvēlētos stāvvietu, kurai veik rezervāciju, nospiediet uz stāvvietas kastes. Tad, nospiežiet pogu rezervēt. Ja kaste ir dzeltenā krāsā, tā pašlaik nav aizņemta.
        Ja kaste ir tumši zilā krāsā, pašlaik šī stāvvieta ir aizņemta, bet iespējams vēlāk tā būs atkal pieejama.
    </div>
    <div id="buffer2" class="col bg-info"></div>
    </div>
    <div id="row2" class="row min-vw-100">
    <div id="buffer3" class="col bg-info"></div>
    <p id="pageTitle" class="col-6 text-dark text-center">Autostāvvietu saraksts: </p>
    <div id="buffer4" class="col bg-info"></div>
    </div>
    <div id="row3" class="row min-vw-100 min-vh-100">
    <div id="buffer5" class="col bg-info"></div>
    <div id="lotList" class="col-6 px-3 py-3 text-dark text-center">
        <div id="lotListBox" class="row row-cols-3 gx-2 gy-2">

        </div>
        <a id="createLot" href="ParkingLotCreation.php" class="btn btn-info mx-auto invisible">Pievienot jaunu stāvlaukumu</a>
    </div>
    <div id="buffer6" class="col bg-info"></div>
    </div>
</section>
<script>
    $(function(){
        let userStatus=-1;
        $.ajax({
            type: "POST",
            url:"../Controllers/AjaxController.php",
            async:true,
            data: "action=userGet",
            success: function(data){
                let user = JSON.parse(data);
                userStatus = user.status;
                if(user.status>0){
                    document.getElementById("createLot").className="btn btn-info mx-auto visible";
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
                let select = document.getElementById('lotListBox');
                //console.log(typeof json);
                for(let lot of json){
                    //console.log(lot);
                    let lotBox=document.createElement('div');
                    lotBox.id="lotBox"+lot["id"];
                    lotBox.className="p-1 bg-warning text-dark text-center border border-dark border-4";
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
                viewButton.value= "Apskatīt stāvvietas";
                viewButton.className= "btn btn-info border border-dark border-2";

                viewForm.appendChild(lotIdInput);
                viewForm.appendChild(viewButton);
                lotBox.appendChild(viewForm);
        }
        function editForm(lotBox,lot){
            let userId = JSON.parse(<?php if(isset($_SESSION['userId'])){echo json_encode($_SESSION['userId']);}
            else echo json_encode(null);?>);
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
                editButton.value= "Rediģēt datus";
                editButton.className= "btn btn-info border border-dark border-2";

                editForm.appendChild(lotIdInput);
                editForm.appendChild(lotAddressInput);
                editForm.appendChild(lotSpacesInput);
                editForm.appendChild(lotRateInput);
                editForm.appendChild(editButton);
                lotBox.appendChild(editForm);
            }
        }
        function removeButton(lotBox,lot){
            let userId = JSON.parse(<?php if(isset($_SESSION['userId'])){echo json_encode($_SESSION['userId']);}
                                        else echo json_encode(null);?>);
            if(userId === lot.owner_id || userStatus === 2){
                let removeButton=document.createElement('button');
                removeButton.id = "removeButton"+lot.id;
                removeButton.value= "Izdzēst stāvlaukumu";
                removeButton.className= "btn btn-info border border-dark border-2";
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