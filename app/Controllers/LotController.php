<?php

namespace App\Controllers;

require_once "../../vendor/autoload.php";

use Datetime;
use App\Models\ParkingLot;
use App\Models\ParkingSpace;

class LotController{

    public function getSpaces(int $id)
    {
        /*
         * Iegūt sarakstu ar stāvvietām, kas pieder stāvlaukumam, sagatavot datus lapai
         * */
        $data = null;
        if((new ParkingLot)->lotExists($id))
        {
            $spaces = (new ParkingSpace)->getSpaceFromIds($id);
            foreach($spaces as $space)
            {
                $data[]=[
                    'id' => $space->id,
                    'number' => $space->number,
                    'lot_id' => $space->lot_id,
                    'reservationStatus' => $space->reservation_status,
                ];
            }
        }
        return $data;
    }

    public function getLotList()
    {
        /*
         * Iegūt sarakstu ar visiem stāvlaukumiem, to adresēm un vietu skaitu.
         * */
        $data = [];
        $lots = (new ParkingLot())->getLotList();
        foreach($lots as $lot) {
            $data[] = $lot;
        }
        return $data;
    }

    public function addLot(string $address, int $numberOfSpaces, float $hourly_rate, int $userId) : bool
    {
        /*
         * Pārbaudīt datus, padot modeļa metodei, kura uztaisītu jaunu stāvlaukumu
         * */
        if(strlen($address)>75 || $numberOfSpaces<1 || $hourly_rate < 0){
            return false;
        }
        return (new ParkingLot)->addLot($address, $numberOfSpaces, $hourly_rate,$userId);
    }

    public function editLot(int $id, string $address, int $numberOfSpaces, float $hourly_rate) : bool
    {
        /*
         * Nomainīt informāciju par stāvlaukumu, vai vietu skaitu izmainīt, vai nomainīt maksas rādītāju
         * */
        if(strlen($address)>75 || $numberOfSpaces<1 || $hourly_rate < 0){
            return false;
        }
        $status1 = $status2 = $status3 = true;
        //Izveidot ParkingLot objektu no id, un salīdzināt datus.
        $lot = (new ParkingLot)->getLot($id);
        if($address !== $lot->address){
            $status1 = $lot->changeAddress($id,$address);
        }
        if($numberOfSpaces !== $lot->space_count){
            $status2 = $lot->changeNumberOfSpaces($id,$numberOfSpaces);
        }
        if($hourly_rate !== $lot->hourly_rate){
            $status3 = $lot->changeHourlyRate($id,$hourly_rate);
        }
        return ($status1 && $status2 && $status3);
    }

    public function removeLot(int $id)
    {
        /*
         * izdzēst visas stāvvietas, kas saistītas ar stāvlaukumu, tad izdzēst pašu stāvlaukumu
         * */
        if((new ParkingLot)->lotExists($id))
        {
            if((new ParkingSpace)->removeSpacesOnLotDeletion($id)){
                return (new ParkingLot)->removeLot($id);
            }
            return false;
        }
        return false;
    }
}
