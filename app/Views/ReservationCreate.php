<?php
session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true || !isset($_SESSION['userId'])) {
    header ("Location: Login.php");
    die();
}
include ('Header.php');
?>
<div>
    <p>Šeit varēs izveidot rezervāciju</p>
</div>
<div id="userReservationList">

</div>
<script>
    document.getElementById("from").addEventListener("input",changeMaxMinDate,false);
    let loggedIn = false;
    $.ajax({
        type: "POST",
        url:"../Controllers/AjaxController.php",
        async:true,
        data: "action=userGet",
        success: function(data){
            let user = JSON.parse(data);
            if(user.status>0){
                loggedIn = true;
                $('#logIn').removeClass(" visible").addClass(" invisible");
                $('#logOut').removeClass(" invisible").addClass(" visible");
                $('#userProfile').removeClass(" invisible").addClass(" visible");
            }
        }
    });
    $("#reservationCreateCreate").submit(function(event) {

        event.preventDefault(); // avoid to execute the actual submit of the form.

        $.ajax({
            type: "GET",
            url: "../Controllers/AjaxController.php",
            data: {
                'from': $("#from").val(),
                'till': $("#till").val(),
                'spaceId': <?php echo $_GET['space_id'] ?>,
                'action' : 'reservationCreate',
            },
            success: function(data)
            {
                //redirect uz homepage, tagad logged in/signed up.
            }
        });

    });
    function changeMaxMinDate(){
        /*
        Paņem min date, pieliek mēnesi klāt, ja tas ir senāk par šodienu, noliek max uz
        to datumu. Arī pie reizes uzliek min vērtību, kas ir vienāda ar from datumu.
         */
        let minDate = new Date(document.getElementById("from").value);
        let today = returnDateString(minDate);
        document.getElementById("till").min=today;
        let newDate = minDate;
        newDate.setMonth(newDate.getMonth()+1);
        let currentDate = new Date();
        //nomainīt, ka funkcija nosaka max date mēnesi uz priekšu? vai dienu uz priekšu no esošā datuma
        //also vajadzētu iegūt rezervācijas, lai pateiktu, vai tas laiks ir pieejams, vai nē
        if(newDate<currentDate){
            today=returnDateString(newDate);
            document.getElementById("till").max=today;
            document.getElementById("till").value=today;
        }
        else{
            today=returnDateString(currentDate);
            document.getElementById("till").max=today;
            minDate.setMonth(minDate.getMonth()-1);
            if(minDate>new Date(document.getElementById("till").value)){
                document.getElementById("till").value=returnDateString(minDate);
            }
        }
    }

    function returnDateString(currentDate){
        let d=currentDate.getDate();
        let m=currentDate.getMonth()+1;//js shenanigans
        let y=currentDate.getFullYear();
        let h=currentDate.getHours();
        let min=currentDate.getMinutes();
        if(d<10){
            d= '0'+d;
        }
        if(m<10){
            m = '0'+m;
        }
        if(min<10){
            min = '0'+min;
        }
        if(h<10){
            h = '0'+h;
        }
        return y+'-'+m+'-'+d+'T'+h+':'+min;
    }
</script>
</html>
