<?php

session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
include ('Header.php');
?>
<section id="main" class="container-fluid row min-vh-100 min-vw-100">
    <div id="buffer" class="col"></div>
    <div class="col-6">
        <form id="reservationCreate" method="post" action="../Controllers/AjaxController.php">
            <label for="day" class="form-label">Rezervācijas sākuma diena:</label><br>
            <input type="date" id="dayFrom" name="day" min="" max=""><br>
            <label for="from" class="form-label">Rezervācijas laika sākums:</label><br>
            <input type="text" id="from" class="timepicker" name="from" min="" max=""><br>
            <div id="fromError"></div>
            <label for="till" class="form-label">Rezervācijas laika beigas:</label><br>
            <input type="text" id="till" class="timepicker" name="till" min="" max=""><br><br>
            <div id="tillError"></div>
            <input type="submit" value="Rezervēt">
        </form>
    </div>
    <div id="buffer2" class="col"></div>
</section>
<script>
    $(function() {
        if(document.getElementById("dayFrom").value===""){
            setMinDate();
        }
        let spaceId = JSON.parse(<?php echo json_encode($_POST['spaceId']) ?>);
        //Uz dienas nomaiņu, jauns ajax request ar visiem rezervāciju pieprasījumiem tai stāvvietai konkrētajā dienā?
        let userId = JSON.parse(<?php echo json_encode($_SESSION['userId']);?>);
        let day = document.getElementById("dayFrom").value.toString();
        let disabledTimes = [];
        $("#day").change(function(){
            $.ajax({
                type: "POST",
                url:"../Controllers/AjaxController.php",
                async:true,
                data: {
                    'action' : "spaceReservationLoad",
                    'spaceId' : spaceId,
                    'userId' : userId,
                    'day' : day,
                },
                success: function(data){
                    let array = JSON.parse(data);
                    //console.log(array);
                    console.log(data);
                    for(let reservation of array){
                        let disabledTimeInterval = {
                            'from' : reservation.from,
                            'till' : reservation.till,
                        };
                        disabledTimes.push(disabledTimeInterval);
                    }
                }
            });
        });
        let fromTime;
        let tillTime;
        $('#from.timepicker').timepicker({
            timeFormat: 'HH:mm:ss',
            interval: 30,
            minTime: '0',
            maxTime: '23:30',
            defaultTime: '0',
            startTime: '00:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true,
            change: function(time) {
                fromTime = time.toISOString();

                for(let interval of disabledTimes){
                    console.log(typeof interval.from);
                    console.log(typeof interval.till);
                    if(interval.from >= from || interval.till <= from){
                        let errorText = document.createTextNode("Sākuma laiks nav pieejams!")
                        document.getElementById("fromError").appendChild(errorText);
                    }
                }
            }
        });
        $('#till.timepicker').timepicker({
            timeFormat: 'HH:mm:ss',
            interval: 30,
            minTime: '0',
            maxTime: '23:30',
            defaultTime: '0',
            startTime: '00:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true,
            change: function(time) {
                tillTime = time.toISOString();

                for(let interval of disabledTimes){
                    if(interval.from >= time || interval.till <= time || time <= $('#from').value.toString()){
                        let errorText = document.createTextNode("Beigu laiks nav pieejams!");
                        document.getElementById("tillError").appendChild(errorText);
                    }
                }
            }
        });
        $("#reservationCreate").submit(function (e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.
            let dayFrom = new Date($("#dayFrom").val());

            let realFrom = new Date(fromTime), realTill = new Date(tillTime);

            realFrom.setFullYear(dayFrom.getFullYear());
            realTill.setFullYear(dayFrom.getFullYear());

            realFrom.setMonth(dayFrom.getMonth());
            realTill.setMonth(dayFrom.getMonth());

            realFrom.setDate(dayFrom.getDate());
            realTill.setDate(dayFrom.getDate());

            if(checkReservation(realFrom,realTill)){
                let from = returnDateStringFull(realFrom);
                let till = returnDateStringFull(realTill);
                let spaceId = JSON.parse(<?php echo json_encode($_POST['spaceId']);?>);
                let userId = JSON.parse(<?php echo json_encode($_POST['userId']);?>);
                console.log(from+","+till+","+spaceId+","+userId)
                $.ajax({
                    type: "POST",
                    url: "../Controllers/AjaxController.php",
                    data: {
                        'from': from,
                        'till': till,
                        'spaceId': spaceId,
                        'userId': userId,
                        'action': 'reservationCreate',
                    },
                    //dataType: "json",
                    success: function (response) {
                        let data = JSON.parse(response);
                        //console.log(typeof data);
                        if(typeof data === 'boolean'){
                            let lotId=JSON.parse(<?php echo json_encode($_POST['lotId']); ?>);
                            window.location = 'ParkingSpaceOverview.php?lotId='+lotId;
                        }
                    },
                    error: function (response) {
                        let error = JSON.parse(response.responseText);
                        //console.log(response.responseText);
                        alert(error);
                    },
                });
            }
        });
        function checkReservation(from,till){
            if(till<from){
                alert("Rezervācijas beigu laiks ir pirms sākuma laika!")
                return false;
            }
            return true;
        }
        function setMinDate(){
            let today = new Date();
            let todayString = returnDateString(today);
            document.getElementById("dayFrom").setAttribute("min", todayString);
            document.getElementById("dayFrom").setAttribute("value", todayString);
        }

        function returnDateString(currentDate){
            let d=currentDate.getDate();
            let m=currentDate.getMonth()+1;//js shenanigans
            let y=currentDate.getFullYear();
            if(d<10){
                d= '0'+d;
            }
            if(m<10){
                m = '0'+m;
            }
            return y+'-'+m+'-'+d;
        }

        function returnDateStringFull(currentDate){
            let d=currentDate.getDate();
            let m=currentDate.getMonth()+1;
            let y=currentDate.getFullYear();
            let h=currentDate.getHours();
            let min=currentDate.getMinutes();
            let sec=currentDate.getSeconds();
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
            if(sec<10){
                sec = '0'+sec;
            }
            return y+'-'+m+'-'+d+' '+h+':'+min+':'+sec;
        }
    });
</script>
</html>

