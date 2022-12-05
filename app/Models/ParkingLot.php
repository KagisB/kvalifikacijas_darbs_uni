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
            return true;
        }
        $connection->close();
        return false;
    }

    public function removeLot(int $lot_id)
    {
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('DELETE FROM ParkingLots WHERE id = ?');
        $query->bind_param('i', $lot_id);
        $query->execute();
        return $query;
        /*
         * izdzēst visas stāvvietas, kas saistītas ar stāvlaukumu, tad izdzēst pašu stāvlaukumu
         * */
    }

    public function getSpaceCount(int $lot_id)
    {
        /*
         * Atgriezt stāvvietu skaitu stāvlaukumam
         * */
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('SELECT space_count FROM ParkingLots WHERE id = ?');
        $query->bind_param('i', $lot_id);
        $query->execute();
        $connection->close();
        $result = $query->get_result();
        $row = $result->fetch_array();
        return $row['space_count'];
    }

    public function changeHourlyRate(int $lot_id, int $hourly_rate)
    {
        /*
         * Nomainīt hourly_rate esošam stāvlaukumam
         * */
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('UPDATE ParkingLots SET hourly_rate = ? WHERE id = ?');
        $query->bind_param('ii', $hourly_rate,$lot_id);
        $query->execute();
        $connection->close();
        return $query;//varbūt būs jānomaina uz variable, kas ir vienāds ar to query execute un atgriezt variable
    }
}