<?php

namespace App\Controllers;

use app\Controllers\LoginController;
use app\Controllers\LotController;
use app\Controllers\ReservationController;
use app\Controllers\SpaceController;
use app\Controllers\UserController;
use DateTime;

$errors = [];
$data = [];

if(!empty($_POST['action'])){
    switch($_POST['action']){
        case 'userGet':
            $userInfo = (new UserController)->getUserInfo();
            echo json_encode($userInfo);

            break;
        case 'userLogIn':
            if(isset($_POST['username']) && isset($_POST['password'])) {
                if((new UserController)->logIn()) {
                    echo true;
                }
                $errors['logInDataError']="Nepareizi ievadīti lietotāja dati!";
            }
            $errors['setData']="Nav ievadīti lietotāja dati!";
            echo json_encode($errors);

            break;
        case 'userSignUp':
            if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
                if((new UserController)->signUp()) {
                    echo true;
                }
                //$errors['logInDataError']="Nepareizi ievadīti lietotāja dati!";
                echo false;
            }
            $errors['setData']="Nav ievadīti lietotāja dati!";
            //echo json_encode($errors);
            echo false;

            break;
        case 'userLogOut':
            echo json_encode((new UserController)->logOut());

            break;
        case 'lotLoad':
            echo json_encode((new LotController())->getLotList());

            break;
        case 'lotCreate':
            if(isset($_POST['address']) && isset($_POST['spaceCount']) && isset($_POST['hourlyRate'])) {
                if(is_int($_POST['spaceCount']) && is_float($_POST['hourlyRate'])) {
                    if((new LotController())->addLot($_POST['address'],$_POST['spaceCount'],$_POST['hourlyRate'])) {
                        echo true;
                    }
                    echo false;
                }
                echo false;
            }
            echo false;
            break;
        case 'reservationCreate':
            if(isset($_GET['from']) && isset($_GET['till']) && isset($_GET['spaceId'])) {
                if((new ReservationController)->createReservation()) {
                    echo true;
                }
                echo false;
            }
            echo false;
            break;
    }
}