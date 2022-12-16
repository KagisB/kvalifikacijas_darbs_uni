<?php
require '../Controllers/UserController.php';
use Router\Router;

if(!empty($_POST['name']) && !empty($_POST['password'])){
    if($this->validateInput($_POST['name'],$_POST['password'])){
        session_start();
        $_SESSION['userlogin']=$_POST['name'];
        Router::contentToRender();
    }
    else{
        $_SESSION['userlogin']=FALSE;
    }
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
    Username:<input type="text" id="name" name="name"><br>
    Password:<input type="password" id="password" name="password"<br>
    <input type="submit" name="Log in">
</form>
</html>
<script>
    $("#signInForm").submit(function(event) {

        event.preventDefault(); // avoid to execute the actual submit of the form.

        $.ajax({
            type: "POST",
            url: "../Controllers/AjaxController.php",
            data: {
                'username': $("#name").val(),
                'password': $("#password").val(),
                'action' : 'userLogIn',
            },
            success: function(data)
            {
                //redirect uz homepage, tagad logged in/signed up.
            }
        });

    });
</script>