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
    <title>Login</title>
</head>
<div id="Intro">
    <p>Autorizācijas view.</p>
</div>
<form id="signInForm" method = post action = Login.php>
    Username:<input type="text" name="name"><br>
    Password:<input type="password" name="password"<br>
    <input type="submit" name="Log in">
</form>
</html>
<script>

</script>