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
        if($this->validateInput($user_id,$space_id, $from, $till)){
            $fromDate = date('Y-m-d H:i:s',strtotime($from));
            $tillDate = date('Y-m-d H:i:s',strtotime($till));
            return (new Reservation())->addReservation($user_id,$space_id,$fromDate,$tillDate);
        }
        return false;
    }

    public function showSpaceReservations(int $userId, int $spaceId, string $from, string $till)
    {
        /*
         * Parādīt rezervācijas noteiktajā laika periodā konkrētā periodā, kad validēts periods.
         * */
        $data = [];
        $user_id = $userId;
        $space_id = $spaceId;
        if($this->validateInput($user_id,$space_id, $from, $till)){
            $fromDate = date('Y-m-d H:i:s',strtotime($from));
            $tillDate = date('Y-m-d H:i:s',strtotime($till));
        }
        $reservations = (new Reservation())->getSpaceReservationsInTimePeriod($space_id,$fromDate,$tillDate);
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

    public function showUserReservations(int $userId)
    {
        $data = [];
        $user_id = $userId;
        if(!(new User)->userExists($user_id)) {
            return $data;
        }
        $reservations = (new Reservation())->getReservationsByUserId($user_id);
        foreach ($reservations as $reservation) {
            $data[] = $reservation;
        }
        return $data;
    }

    public function validateInput(int $userId,int $spaceId, string $from, string $till) :bool
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
        if(!(new ParkingSpace)->spaceExists($spaceId)) {
            echo "Error: Space does not exist";
            return false;
        }
        if(!(new User)->userExists($userId)) {
            echo "Error: User does not exist";
            return false;
        }
        if(!date('Y-m-d H:i:s',strtotime($from))){
            echo "unable to create date from";
            return false;
        }
        if(!date('Y-m-d H:i:s',strtotime($till))){
            echo "unable to create date till";
            return false;
        }
        if($tillDate<$fromDate || $tillDate===$fromDate) {
            echo "Error: Period start is after period end or exactly the same";
            return false;
        }
        $interval = $fromDate->diff($tillDate);
        if($interval->d > 31) {
            echo "Error: Period length is longer than 31 days!";
            return false;
        }
        return true;
    }
}