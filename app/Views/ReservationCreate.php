<?php
?>
<html lang="lv">
<head>
    <script src="https://code.jquery.com/jquery-3.6.2.js"
            integrity="sha256-pkn2CUZmheSeyssYw3vMp1+xyub4m+e+QK4sQskvuo4="
            crossorigin="anonymous"></script>
    <title>Autostāvlaukuma izveide</title>
</head>
<div>
    <p>Šeit būs input form, lai izveidou jaunu rezervāciju</p>
</div>
<form id="reservationCreate" method="post" action="ReservationCreate.php">
    <label for="spaceCount">Rezervācijas sākums:</label><br>
    <input type="datetime-local" id="from" name="from"><br>
    <label for="hourlyRate">Rezervācijas beigas:</label><br>
    <input type="datetime-local" id="till" name="till" min="" max=""><br><br>
    <input type="submit" value="Iesniegt">
</form>
<script>
    document.getElementById("from").addEventListener("input",changeMaxMinDate,false);
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
