<?php
require_once '../vendor/autoload.php';
/*
 Adds routes with the help of FastRoutes package, to help redirect pages to other pages,
 without needing to do so in every file individually.
*/
class Router{
    private $dispatcher;
    public function __construct(){
        $this->dispatcher = FastRoute\simpleDispatcher(function($r) {
            $r->get('/public/index.php', 'sendToIndex');
            $r->get('/', 'sendToIndex');
            $r->get('/index.php', 'sendToIndex');
            $r->post('/ParkingLotList.php', 'sendToLotList');
            $r->post('/app/views/Login.php', 'sendToLogin');
            $r->post('/app/views/ParkingLotCreation.php', 'sendToLotCreation');
            $r->post('/app/views/ParkingSpaceOverview.php', 'sendToSpaceOverview');
            $r->post('/app/views/Signup.php', 'sendToSignup');
            $r->post('/app/views/UserProfileView.php', 'sendToUser');
            $r->post('/app/views/ParkingSpaceReservation.php', 'sendToReservation');//{id:\d+}

            $r->post('/app/lot/{lotId:\d+}', 'sendToSpaceOverview');
            $r->post('/app/user/{userId:\d+}', 'sendToUser');
            $r->post('/app/lot/{lotId:\d+}/space/{spaceId:\d+}', 'sendToReservation');
            /*$r->post('/app/views/ParkingLotList.php', 'sendToLotList');
            $r->post('/app/views/Login.php', 'sendToLogin');
            $r->post('/app/views/ParkingLotCreation.php', 'sendToLotCreation');
            $r->post('/app/views/ParkingSpaceOverview.php', 'sendToSpaceOverview');
            $r->post('/app/views/Signup.php', 'sendToSignup');
            $r->post('/app/views/UserProfileView.php', 'sendToUser');
            $r->post('/app/views/ParkingSpaceReservation.php', 'sendToReservation');//{id:\d+}

            $r->post('/app/lot/{lotId:\d+}', 'sendToSpaceOverview');
            $r->post('/app/user/{userId:\d+}', 'sendToUser');
            $r->post('/app/lot/{lotId:\d+}/space/{spaceId:\d+}', 'sendToReservation');*/
        });
    }
    public function dispatchRoute($uri,$requestMethod){
        $httpMethod = $requestMethod;
        $uri = cleanURI($uri);
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                echo "404 Not Found";
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                echo "405 Method Not Allowed";
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                call_user_func($handler,$vars);
                break;
        }
    }
}
function cleanURI($uri){
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    return $uri;
}
function sendToIndex(){
    header('Location: ../app/Views/Index.php');
    //header('Location: index.php');
    exit();
}
function sendToLogin(){
    header('Location: ../app/Views/Login.php');
    exit();
}
function sendToLotList(){
    header('Location: ../app/Views/ParkingLotList.php');
    exit();
}
function sendToLotCreation(){
    header('Location: ../app/Views/ParkingLotCreation.php');
    exit();
}
function sendToSpaceOverview(?int $lotId){
    header('Location: ../app/Views/ParkingSpaceOverview.php?lotId='.$lotId);
    exit();
}
function sendToSignup(){
    header('Location: ../app/Views/Signup.php');
    exit();
}
function sendToUser(){
    header('Location: ../app/Views/UserProfileView.php');
    exit();
}
function sendToReservation(?int $lotId, ?int $spaceId){
    header('Location: ../app/Views/ParkingSpaceReservation?lotId='.$lotId."&spaceId=".$spaceId);
    exit();
}