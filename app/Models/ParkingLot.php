<?php

namespace app\Models;
use App\Models\DBConnection;
use App\Models\ParkingSpace;

class ParkingLot{
    private int $id;
    private int $space_count;
    private int $hourly_rate;
    private string $address;

    public function __construct()
    {
        $this->id = 0;
        $this->address = '';
        $this->space_count = 0;
        $this->hourly_rate = 0;
    }

    public function addLot(string $address, int $numberOfSpaces, float $hourly_rate)
    {
        /*
         * pievienot stāvlaukumu datubāzē, kad stāvlaukums izveidots, izveidot stāvvietas caur
         * ParkingSpace model
         * */
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('INSERT INTO ParkingLots VALUES (?,?,?)');
        $query->bind_param('sii', $address, $numberOfSpaces, $hourly_rate);
        $query->execute();
        if($query->result_metadata()){
            (new ParkingSpace)->addSpacesOnLotCreation($query->insert_id,$numberOfSpaces);
        }
        $connection->close();
        return false;
    }

    public function getSpaceCount(int $lotId)
    {
        /*
         * Atgriezt stāvvietu skaitu stāvlaukumam
         * */
    }

    public function changeHourlyRate(int $lot_id, int $hourly_rate)
    {
        /*
         * Nomainīt hourly_rate esošam stāvlaukumam
         * */
    }
}