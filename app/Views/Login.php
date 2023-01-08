<?php
session_start();
if (isset($_SESSION['logInStatus']) && $_SESSION['logInStatus'] === true) {
    header ("Location: Index.php");
    die();
}
include ('Header.php');
?>
<div id="Intro">
    <p>Autorizācijas view.</p>
</div>
<form id="signInForm" method = post action = ../Controllers/AjaxController.php>
    Lietotājvārds:<input type="text" id="name" name="name" required><br>
    Parole:<input type="password" id="password" name="password" required><br>
    <input type="submit" name="Ieiet">
</form>
<div>
    Neesi reģistrējies? <a href="Signup.php">Piereģistrējies šeit</a>
</div>
</html>
<script>
    $("#signInForm").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        let name = $("#name").val();
        let password = $("#password").val().toString();
        $.ajax({
            type: "POST",
            url: "../Controllers/AjaxController.php",
            data: {
                'username': name,
                'password': password,
                'action' : 'userLogIn',
            },
            dataType: "json",
            success: function(response)
            {
                //console.log(response);
                //alert("success");
                window.location = 'Index.php';
            },
            error: function(response)
            {
                //console.log(response);
                alert("error")
            },
        });

    });
</script>