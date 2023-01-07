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
}
