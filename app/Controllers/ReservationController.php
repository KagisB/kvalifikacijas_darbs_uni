<?php

namespace app\Controllers;

use app\Controllers\UserController;
use app\Models\Reservation;
use app\Models\ParkingSpace;
use app\Models\User;
use Datetime;

class ReservationController{
    public function createReservation() : bool
    {
        /*
         * Pārveidot esošos datus no lietotāja un izveidot rezervāciju
         * */
        //$_GET['user_id','space_id','from','till']
        //User id varbūt varētu iegūt no citas funkcijas, iegūt esošā lietotāja id, tas būtu efektīvāk
        //$user_id = getCurrentUserId();
        $user_id = (new UserController)->getUserId();
        $from = $_GET['from'];//potenciāli pārveidot uz datetime, ja padotais variable ir string
        $till = $_GET['till'];
        $space_id = $_GET['space_id'];
        if(!(new ParkingSpace)->spaceExists($space_id)) {
            //echo "Error: Space does not exist";
            return false;
        }
        if($_GET["from"]!=null){
            try {
                $from = new DateTime($_GET["from"]);
            } catch (\Exception $e) {
            }
        }
        else{
            $from = new DateTime("now");
            $from->modify("-1 week");
        }
        if($_GET["till"]!=null){
            try {
                $till = new DateTime($_GET["till"]);
            } catch (\Exception $e) {
            }
        }
        $interval = $from->diff($till);
        if($interval->d > 31) {
            //echo "Error: Period length is longer than 31 days!";
            return false;
        }
        return (new Reservation())->addReservation($user_id,$space_id,$from,$till);
    }

    public function showSpaceReservations()
    {
        /*
         * Parādīt rezervācijas noteiktajā laika periodā konkrētā periodā, kad validēts periods.
         * */
        $data = [];
        $from = $_GET['from'];//potenciāli pārveidot uz datetime, ja padotais variable ir string
        $till = $_GET['till'];
        $space_id = $_GET['space_id'];
        if(!(new ParkingSpace)->spaceExists($space_id)) {
            echo "Error: Space does not exist";
            return $data;
        }
        if($till<$from) {
            echo "Error: Period start is after period end";
            return $data;
        }
        $interval = $from->diff($till);
        if($interval->d > 31) {
            echo "Error: Period length is longer than 31 days!";
            return $data;
        }
        $reservations = (new Reservation())->getSpaceReservationsInTimePeriod($space_id,$from,$till);
        foreach ($reservations as $reservation) {
            /*$data[]=[
                'id' => $reservation['id'],
                'user_id' => $reservation['user_id'],
                'space_id' => $reservation['space_id'],
                'from' => $reservation['from'],
                'till' => $reservation['till'],
            ];*/
            $data[] = $reservation;
        }
        return $data;
    }

    public function showUserReservations()
    {
        $data = [];
        $from = $_GET['from'];//potenciāli pārveidot uz datetime, ja padotais variable ir string
        $till = $_GET['till'];
        $user_id = $_GET['space_id'];
        if(!(new User)->userExists($user_id)) {
            echo "Error: Space does not exist";
            return $data;
        }
        if($till<$from) {
            echo "Error: Period start is after period end";
            return $data;
        }
        $interval = $from->diff($till);
        if($interval->d > 31) {
            echo "Error: Period length is longer than 31 days!";
            return $data;
        }
        $reservations = (new Reservation())->getReservationsByUserId($user_id,$from,$till);
        foreach ($reservations as $reservation) {
            $data[] = $reservation;
        }
        return $data;
    }
}