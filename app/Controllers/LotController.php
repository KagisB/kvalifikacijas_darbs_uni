<?php

namespace App\Controllers;

require_once "../../vendor/autoload.php";

use Datetime;
use App\Models\ParkingLot;
use App\Models\ParkingSpace;

class LotController{
    public function getBusynessData(Datetime $from, Datetime $till)
    {
        /*
         * Iegūt sarakstu ar aizņemtību noteiktajā periodā, un sagatavot datus lapai
         * */
    }

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
        return json_encode($data);
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

    public function addLot(string $address, int $numberOfSpaces, float $hourly_rate) : bool
    {
        /*
         * Pārbaudīt datus, padot modeļa metodei, kura uztaisītu jaunu stāvlaukumu
         * */
        if(strlen($address)>75 || $numberOfSpaces<1 || $hourly_rate < 0){
            return false;
        }
        return (new ParkingLot)->addLot($address, $numberOfSpaces, $hourly_rate);
    }

    public function editLot(int $id, string $address, int $numberOfSpaces, float $hourly_rate)
    {
        /*
         * Nomainīt informāciju par stāvlaukumu, vai vietu skaitu izmainīt, vai nomainīt maksas rādītāju
         * */
        if(strlen($address)>75 || $numberOfSpaces<1 || $hourly_rate < 0){
            return false;
        }
        //Izveidot ParkingLot objektu no id, un salīdzināt datus.
        $lot = (new ParkingLot)->getLot($id);
        if($address !== $lot->address){
            $lot->changeAddress($id,$address);
        }
        if($numberOfSpaces !== $lot->space_count){
            $lot->changeNumberOfSpaces($id,$numberOfSpaces);
        }
        if($hourly_rate !== $lot->hourly_rate){
            $lot->changeHourlyRate($id,$hourly_rate);
        }
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
