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
$_POST['spaceId']=27;
$_POST['from']="2023-01-06 05:30:00";
$_POST['till']="2023-01-06 10:30:00";*/
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
                if((new UserController)->signUp($_POST['username'],$_POST['password'],$_POST['email'])) {
                    echo json_encode(true);
                    break;
                }
                $errors['logInDataError']="Nepareizi ievadīti lietotāja dati!";
                $jErrors = json_encode($errors);
                echo $jErrors;

                break;
            }
            $errors['setData']="Nav ievadīti lietotāja dati!";
            //echo json_encode($errors);
            $jErrors = json_encode($errors);

            echo $jErrors;

            break;
        case 'userEdit':
            if(isset($_POST['username']) || isset($_POST['password']) || isset($_POST['email'])){
                if((new UserController)->checkEditUserInfo($_POST['username'],$_POST['password'],$_POST['email'])){
                    echo json_encode(true);
                    break;
                }
                $errors['editFail'] = "Nav ievadīti lietotāja dati!";
                $jErrors = json_encode($errors);

                echo $jErrors;
                break;
            }
            $errors['setData'] = "Kļūda datu rediģēšanā";
            $jErrors = json_encode($errors);

            echo $jErrors;
            break;
        case 'userLogOut':
            echo json_encode((new UserController)->logOut());

            break;
        case 'userDelete':
            if(isset($_POST['userId'])){
                echo json_encode((new UserController)->deleteUser($_POST['userId']));

                break;
            }
            break;
        case 'lotLoad':
            echo json_encode((new LotController())->getLotList());

            break;
        case 'lotCreate':
            if(isset($_POST['address']) && isset($_POST['spaceCount']) && isset($_POST['hourlyRate'])) {
                $userId = (new UserController())->getUserId();
                if((new LotController())->addLot($_POST['address'],$_POST['spaceCount'],$_POST['hourlyRate'],$userId)) {
                    echo json_encode(true);

                    break;
                }
                $errors['lotCreate']="Kļūda stāvlaukuma izveidē!";
            }
            $errors['setData']="Nav ievadīti stāvlaukuma dati!";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
        case 'lotEdit':
            if(isset($_POST['lotId']) && (isset($_POST['address']) || isset($_POST['spaceCount']) || isset($_POST['hourlyRate']))){
                if((new LotController())->editLot($_POST['lotId'],$_POST['address'],$_POST['spaceCount'],$_POST['hourlyRate'])){
                    echo json_encode(true);

                    break;
                }
                $errors['lotEdit']="Kļūda stāvlaukuma rediģēšanā!";
            }
            $errors['setData']="Nav ievadīti stāvlaukuma dati!";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
        case 'lotRemove':
            if(isset($_POST['lotId'])){
                echo json_encode((new LotController())->removeLot($_POST['lotId']));

                break;
            }

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
                //echo json_encode((new ReservationController)->showSpaceReservations());
                echo json_encode(true);
                break;
            }

            break;
        case 'reservationCreate':
            if(isset($_POST['from']) && isset($_POST['till']) && isset($_POST['spaceId']) && isset($_POST['userId'])) {
                $userId = intval($_POST['userId']);
                $spaceId = intval($_POST['spaceId']);
                if((new ReservationController)->createReservation($userId,$spaceId,$_POST['from'],$_POST['till'])) {
                //if((new ReservationController)->createReservation($_POST['userId'],$_POST['spaceId'],$_POST['from'],$_POST['till'])) {
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
        case 'reservationLoadUser':
            if(isset($_POST['userId'])) {
                echo json_encode((new ReservationController)->showUserReservations($_POST['userId']));

                break;
            }
            $errors['setData']="Nav lietotājs!";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
        case 'spaceReservationLoad':
            if(isset($_POST['spaceId'])) {
                echo json_encode((new ReservationController)->showSpaceReservations($_POST['userId'],$_POST['spaceId'],$_POST['day']));

                break;
            }
            $errors['setData']="Nav stāvvietas!";
            $jErrors = json_encode($errors);
            echo $jErrors;

            break;
    }
}