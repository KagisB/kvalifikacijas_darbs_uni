<?php

session_start();
if (!isset($_SESSION['logInStatus']) || $_SESSION['logInStatus'] !== true) {
    header ("Location: Login.php");
    die();
}
include ('Header.php');
?>
<html lang="lv">
<head>
    <script src="https://code.jquery.com/jquery-3.6.2.js"
            integrity="sha256-pkn2CUZmheSeyssYw3vMp1+xyub4m+e+QK4sQskvuo4="
            crossorigin="anonymous"></script>
    <title>Autostāvlaukuma izveide</title>
</head>
<div>
    <p>Šeit būs input form, lai izveidotu jaunu stāvlaukumu</p>
</div>
<form id="editForm" method="post" action="../Controllers/AjaxController.php">
    <label for="username">Lietotājvārds:</label><br>
    <input type="text" id="username" name="username" maxlength="25" minlength="6" value=""><br>
    <label for="email">E-pasts:</label><br>
    <input type="email" id="email" name="email" minlength="3" maxlength="64" value=""><br>
    <label for="password">Jaunā parole:</label><br>
    <input type="password" id="password" name="password" minlength="8" maxlength="25"><br>
    <label for="passwordRepeat">Jaunā parole atkārtoti:</label><br>
    <input type="password" id="passwordRepeat" name="passwordRepeat" minlength="8" maxlength="25"><br>
    <input type="submit" value="Rediģēt">
</form>
<button id="deleteButton">
    Dzēst kontu.
</button>
<script>
    $(function() {
        document.getElementById("username").value = JSON.parse(<?php echo json_encode($_POST['username'])?>);
        document.getElementById("email").value = JSON.parse(<?php echo json_encode($_POST['email'])?>);
        document.getElementById("deleteButton").addEventListener("click",deleteUser,false);
        function deleteUser() {
            let userId = JSON.parse(<?php echo json_encode($_SESSION['userId'])?>);
            $.ajax({
                type: "POST",
                url: "../Controllers/AjaxController.php",
                data: {
                    'userId' : userId,
                    'action': 'userDelete',
                },
                dataType: "json",
                success: function (response) {
                    window.location = 'ParkingLotList.php';
                    //alert("success");
                },
                error: function (response) {
                    //console.log(response);
                    alert("error");
                },
            });
        }
        $("#editForm").submit(function (e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.
            let password = $("#password").val().toString();
            let passwordRepeat = $("#passwordRepeat").val().toString();
            if(password === passwordRepeat){
                let username = $("#username").val().toString();
                let email = $("#email).val().toString();
                $.ajax({
                    type: "POST",
                    url: "../Controllers/AjaxController.php",
                    data: {
                        'username': username,
                        'email': email,
                        'newPassword' : password,
                        'action': 'userEdit',
                    },
                    dataType: "json",
                    success: function (response) {
                        window.location = 'ParkingLotList.php';
                        //alert("success");
                    },
                    error: function (response) {
                        //console.log(response);
                        alert("error");
                    },
                });
            }


        });
    });
</script>
</html>