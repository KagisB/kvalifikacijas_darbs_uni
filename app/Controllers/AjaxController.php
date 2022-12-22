<?php

namespace App\Controllers;

require_once "../../vendor/autoload.php";

use App\Controllers\LotController as LotController;
use App\Controllers\ReservationController as ReservationController;
use App\Controllers\SpaceController as SpaceController;
use App\Controllers\UserController as UserController;
use DateTime;

$errors = [];
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
            if(isset($_GET['lotId'])){

            }

            break;
        case 'spaceInfo':
            if(isset($_GET['spaceId'])){

            }

            break;
        case 'reservationCreate':
            if(isset($_GET['from']) && isset($_GET['till']) && isset($_GET['spaceId'])) {
                if((new ReservationController)->createReservation()) {
                    echo json_encode(true);
                }
                $errors['reservationCreate']="Kļūda rezervācijas izveidē!";
            }
            $errors['setData']="Nav ievadīti stāvlaukuma dati!";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
    }
}