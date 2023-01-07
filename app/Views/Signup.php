<?php

include ('Header.php');
?>
<div id="Intro">
    <p>Reģistrēšanās view.</p>
</div>
<form id="signUpForm" method="post" action="../Controllers/AjaxController.php">
    <label for="username">Lietotājvārds:</label><br>
    <input type="text" id="username" name="username" minlength="6" maxlength="25" ><br>
    <label for="email">E-pasts:</label><br>
    <input type="email" id="email" name="email" minlength="3" maxlength="64"><br>
    <label for="password">Parole:</label><br>
    <input type="password" id="password" name="password" minlength="8" maxlength="25"><br><br>
    <label for="passwordRepeat">Jaunā parole atkārtoti:</label><br>
    <input type="password" id="passwordRepeat" name="passwordRepeat" minlength="8" maxlength="25"><br>
    <input type="submit" value="Reģistrēties">
</form>
<script>
    $("#signUpForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        let username = $("#username").val().toString();
        let email = $("#email").val().toString();
        let password = $("#password").val().toString();
        let passwordRepeat = $("#passwordRepeat").val().toString();
        if(password === passwordRepeat){
            $.ajax({
                type: "POST",
                url: "../Controllers/AjaxController.php",
                data: {
                    'username': username,
                    'email': email,
                    'password': password,
                    'action' : 'userSignUp',
                },
                success: function(data)
                {
                    window.location = 'Index.php';
                    //redirect uz homepage, tagad logged in/signed up.
                }
            });
        }
        else alert("Paroles nesakrīt!");
    });
</script>
</html>