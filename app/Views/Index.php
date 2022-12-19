<?php
session_start();
/*if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}*/
?>
<html lang="lv">

<div id="Intro">
    <p>Sveicināti autostāvietu rezervācijas sistēmā.</p>
</div>
<button type="button" id="signIn">
    <h3>Sign in</h3>
</button>
<button type="button" id="signUp">
    <h3>Sign up</h3>
</button>
<button type="button" id="temp1">
    <h3>Temporary poga priekš citiem page testing1 lotlist</h3>
</button>
<button type="button" id="temp2">
    <h3>Temporary poga priekš citiem page testing2 lotcreate</h3>
</button>
<button type="button" id="temp3">
    <h3>Temporary poga priekš citiem page testing3 spaceoverview</h3>
</button>
<button type="button" id="temp4">
    <h3>Temporary poga priekš citiem page testing4 reservationCreate</h3>
</button>
<script>
    document.getElementById("signIn").addEventListener("click",signInButton,false);
    document.getElementById("signUp").addEventListener("click",signUpButton,false);
    document.getElementById("temp1").addEventListener("click",parkingLotList,false);
    document.getElementById("temp2").addEventListener("click",parkingLotCreate,false);
    document.getElementById("temp3").addEventListener("click",parkingSpaceOverview,false);
    document.getElementById("temp4").addEventListener("click",reservationCreate,false);
function signUpButton(){
    window.location.href = "Signup.php";
}
function signInButton(){
    window.location.href ="Login.php";
}
function parkingLotList(){
    window.location.href = "ParkingLotList.php";
}
function parkingLotCreate(){
    window.location.href = "ParkingLotCreation.php";
}
function parkingSpaceOverview(){
    window.location.href = "ParkingSpaceOverview.php";
}
function reservationCreate(){
    window.location.href = "ReservationCreate.php";
}
</script>