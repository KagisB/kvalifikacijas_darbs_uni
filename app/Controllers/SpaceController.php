<?php

namespace App\Controllers;

require_once "../../vendor/autoload.php";

use App\Models\ParkingSpace;
use App\Models\Reservation;
use Datetime;
class SpaceController{
    public function getInfo(int $space_id)
    {
        /*
         * Iegūt info no model par stāvvietu, vai ir rezervēts,
         * pārveidot šo info, lai attēlotu lietotājam
         * */
        if(!(new ParkingSpace())->spaceExists($space_id)) {
            echo "Error: Space doesn't exist!";
            return null;
        }
        return (new ParkingSpace())->getSpace($space_id);
    }

    public function getReservationsForSpace(int $space_id, Datetime $from, Datetime $till)
    {
        /*
         * Iegūt rezervācijas konkrētai stāvvietai,
         * */
        $data = null;
        if(!(new ParkingSpace())->spaceExists($space_id)) {
            echo "Error: Space doesn't exist!";
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
        $reservations = (new Reservation)->getSpaceReservationsInTimePeriod($space_id,$from, $till);
        foreach($reservations as $reservation){
            $data[]=[
                'id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'space_id' => $reservation->space_id,
                'from' => $reservation->from,
                'till' => $reservation->till,
            ];
        }
        return $data;
    }
}
