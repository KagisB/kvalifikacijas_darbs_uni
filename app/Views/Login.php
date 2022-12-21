<?php
session_start();
if (isset($_SESSION['logInStatus']) && $_SESSION['logInStatus'] === true) {
    header ("Location: Index.php");
    die();
}
?>
<html lang="lv">
<head>
    <script src="https://code.jquery.com/jquery-3.6.2.js"
            integrity="sha256-pkn2CUZmheSeyssYw3vMp1+xyub4m+e+QK4sQskvuo4="
            crossorigin="anonymous"></script>
    <title>Login</title>
</head>
<div id="Intro">
    <p>Autorizācijas view.</p>
</div>
<form id="signInForm" method = post action = ../Controllers/AjaxController.php>
    Username:<input type="text" id="name" name="name" required><br>
    Password:<input type="password" id="password" name="password" required><br>
    <input type="submit" name="Log in">
</form>
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