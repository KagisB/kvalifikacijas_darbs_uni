<?php

namespace App\Controllers;

require_once "../../vendor/autoload.php";

use App\Controllers\UserController as UserController;
use App\Models\Reservation;
use App\Models\ParkingSpace;
use App\Models\User;
use Datetime;

class ReservationController{
    public function createReservation(int $userId, int $spaceId, string $from, string $till) : bool
    {
        /*
         * Pārveidot esošos datus no lietotāja un izveidot rezervāciju
         * */
        $user_id = $userId;
        $space_id = $spaceId;
        if($this->validateInput($spaceId, $from, $till)){
            $fromDate = date('Y-m-d H:i:s',strtotime($from));
            $tillDate = date('Y-m-d H:i:s',strtotime($till));
            return (new Reservation())->addReservation($user_id,$space_id,$fromDate,$tillDate);
        }
        return false;
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

    public function validateInput(int $userId, string $from, string $till) :bool
    {
        try{
            $fromDate = new Datetime($from);
        } catch (\Exception $e) {
            $fromDate = new Datetime('now');
        }
        try {
            $tillDate = new Datetime($till);
        } catch (\Exception $e) {
            $tillDate = new Datetime('now');
        }
        if(!(new User)->userExists($userId)) {
            //echo "Error: Space does not exist";
            return false;
        }
        if(!date('Y-m-d H:i:s',strtotime($from))){
            return false;
        }
        if(!date('Y-m-d H:i:s',strtotime($from))){
            return false;
        }
        if($tillDate<$fromDate || $tillDate===$fromDate) {
            //echo "Error: Period start is after period end";
            return false;
        }
        $interval = $fromDate->diff($tillDate);
        if($interval->d > 31) {
            //echo "Error: Period length is longer than 31 days!";
            return false;
        }
        return true;
    }
}