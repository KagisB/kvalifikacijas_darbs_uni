<?php

namespace app\Models;
use App\Models\DBConnection;
use App\Models\ParkingSpace;

class ParkingLot{
    private int $id;
    private int $space_count;
    private float $hourly_rate;
    private string $address;

    public function __construct(?int $id = 0, ?string $address = '', ?int $space_count = 0, ?float $hourly_rate = 0)
    {
        $this->id = 0;
        $this->address = '';
        $this->space_count = 0;
        $this->hourly_rate = 0;
    }

    public function lotExists(int $id) : bool
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT * FROM ParkingLots WHERE id = ?');
        $query->bind_param('i', $id);
        $query->execute();
        $connection->close();
        return $query;
    }

    public function getLot(int $id) : ?ParkingLot
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT * FROM ParkingLots WHERE id = ?');
        $query->bind_param('i', $id);
        $query->execute();
        $connection->close();
        return $query;
    }

    public function addLot(string $address, int $numberOfSpaces, float $hourly_rate)
    {
        /*
         * pievienot stāvlaukumu datubāzē, kad stāvlaukums izveidots, izveidot stāvvietas caur
         * ParkingSpace model
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
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
        $connection = (new DBConnection())->createMySQLiConnection();
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
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT space_count FROM ParkingLots WHERE id = ?');
        $query->bind_param('i', $lot_id);
        $query->execute();
        $connection->close();
        $result = $query->get_result();
        $row = $result->fetch_array();
        return $row['space_count'];
    }

    public function changeHourlyRate(int $lot_id, float $hourly_rate)
    {
        /*
         * Nomainīt hourly_rate esošam stāvlaukumam
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('UPDATE ParkingLots SET hourly_rate = ? WHERE id = ?');
        $query->bind_param('di', $hourly_rate,$lot_id);
        $query->execute();
        $connection->close();
        return $query;//varbūt būs jānomaina uz variable, kas ir vienāds ar to query execute un atgriezt variable
    }

    public function changeNumberOfSpaces(int $lot_id, int $number_of_spaces)
    {
        /*
         * Nomainīt space number esošam stāvlaukumam
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('UPDATE ParkingLots SET space_number = ? WHERE id = ?');
        $query->bind_param('ii', $number_of_spaces,$lot_id);
        $query->execute();
        $connection->close();
        return $query;//varbūt būs jānomaina uz variable, kas ir vienāds ar to query execute un atgriezt variable
    }
    public function changeAddress(int $lot_id, string $address)
    {
        /*
         * Nomainīt hourly_rate esošam stāvlaukumam
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('UPDATE ParkingLots SET address = ? WHERE id = ?');
        $query->bind_param('si', $address,$lot_id);
        $query->execute();
        $connection->close();
        return $query;//varbūt būs jānomaina uz variable, kas ir vienāds ar to query execute un atgriezt variable
    }
}