<?php

namespace App\Controllers;

require_once "../../vendor/autoload.php";

use App\Controllers\LotController as LotController;
use App\Controllers\ReservationController as ReservationController;
use App\Controllers\SpaceController as SpaceController;
use App\Controllers\UserController as UserController;
use DateTime;

$errors = [];
/*$_POST['action']='reservationCreate';
$_POST['userId']=1;
$_POST['spaceId']=21;
$_POST['from']="27-12-2022 21:25:20";
$_POST['till']="28-12-2022 12:22:23";*/
if(!empty($_POST['action'])){
    switch($_POST['action']){
        case 'userGet':
            $userInfo = (new UserController)->getUserInfo();
            $userInfoJson = json_encode($userInfo);
            echo $userInfoJson;

            break;
        case 'userLogIn':
            if(isset($_POST['username']) && isset($_POST['password'])) {
                if((new UserController)->logIn()){
                    echo json_encode(true);
                    break;
                }
                $errors['logInDataError']="Nepareizi ievadīti lietotāja dati!";
                $jErrors = json_encode($errors);
                echo $jErrors;
            }
            $errors['setData']="Nav ievadīti lietotāja dati!";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
        case 'userSignUp':
            if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
                if((new UserController)->signUp()) {
                    echo json_encode(true);
                    break;
                }
                $errors['logInDataError']="Nepareizi ievadīti lietotāja dati!";
                $jErrors = json_encode($errors);
                echo $jErrors;
            }
            $errors['setData']="Nav ievadīti lietotāja dati!";
            //echo json_encode($errors);
            $jErrors = json_encode($errors);

            echo $jErrors;
        case 'userLogOut':
            echo json_encode((new UserController)->logOut());

            break;
        case 'lotLoad':
            echo json_encode((new LotController())->getLotList());

            break;
        case 'lotCreate':
            if(isset($_POST['address']) && isset($_POST['spaceCount']) && isset($_POST['hourlyRate'])) {
                if((new LotController())->addLot($_POST['address'],$_POST['spaceCount'],$_POST['hourlyRate'])) {
                    echo json_encode(true);

                    break;
                }
                $errors['lotCreate']="Kļūda stāvlaukuma izveidē!";
            }
            $errors['setData']="Nav ievadīti stāvlaukuma dati!";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
        case 'spaceLoad':
            if(isset($_POST['lotId'])){
                echo json_encode((new LotController())->getSpaces($_POST['lotId']));

                break;
            }
            $errors['setData']="Stāvlaukuma id nav padots";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
        case 'spaceInfo':
            if(isset($_GET['spaceId'])){

            }

            break;
        case 'reservationCreate':
            if(isset($_POST['from']) && isset($_POST['till']) && isset($_POST['spaceId']) && isset($_POST['userId'])) {
                if((new ReservationController)->createReservation($_POST['userId'],$_POST['spaceId'],$_POST['from'],$_POST['till'])) {
                    echo json_encode(true);

                    break;
                }
                $errors['reservationCreate']="Kļūda rezervācijas izveidē!";
                $jErrors = json_encode($errors);
                echo $jErrors;

                break;
            }
            $errors['setData']="Nav ievadīti stāvlaukuma dati!";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
    }
}