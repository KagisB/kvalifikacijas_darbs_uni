<?php
session_start();

include ('Header.php');
$isLoggedIn = false;
if(isset($_SESSION['userId'])){
    $isLoggedIn = true;
}
/*echo $_SESSION['userId'];
echo $_SESSION['logInStatus'];*/
?>
<section id="main" class="container-fluid row min-vh-100 min-vw-100">
    <div id="buffer1" class="col bg-info"></div>
    <div id="Intro" class="col-6">
        <p>Sveicināti autostāvietu rezervācijas sistēmā.</p>
        <p>Šajā vietnē jūs varat apskatīties stāvlaukumus un to stāvvietas, kā arī rezervēt sev kādu no šīm stāvvietām uz noteiktu laika periodu
        , ja vēlaties.</p>
    </div>
    <div id="buffer2" class="col bg-info"></div>
</section>
<script>
    $(function(){

    });
</script>