<?php

require '../vendor/autoload.php';
require '../app/Router.php';
//header('Location: ../app/Views/Index.php');
//header('Location: ../public/Index.php');
//exit();
//echo $_SERVER['REQUEST_URI'];
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$route = new Router();
$route->dispatchRoute($uri,$method);

