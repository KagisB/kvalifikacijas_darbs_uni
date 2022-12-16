<?php

namespace app\Models;
use App\Models\DBConnection;
use App\Models\ParkingSpace;

class ParkingLot{
    public int $id;
    public int $space_count;
    public float $hourly_rate;
    public string $address;

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
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        return  new ParkingLot($id,$row['address'],$row['space_number'],$row['hourly_rate']);
    }

    public function getLotList() : array
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT * FROM ParkingLots');
        $query->execute();
        $connection->close();
        $result = $query->get_result();
        $lots = [];
        while($row = $result->fetch_assoc()) {
            $lots[] = [
                'id' => $row['id'],
                'address' => $row['address'],
                'space_number' => $row['space_number'],
                'hourly_rate' => $row['hourly_rate'],
            ];
        }
        return $lots;
    }

    public function addLot(string $address, int $numberOfSpaces, float $hourly_rate) : bool
    {
        /*
         * pievienot stāvlaukumu datubāzē, kad stāvlaukums izveidots, izveidot stāvvietas caur
         * ParkingSpace model
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('INSERT INTO ParkingLots VALUES (?,?,?)');
        $query->bind_param('sii', $address, $numberOfSpaces, $hourly_rate);
        $query->execute();
        $connection->close();
        if($query->result_metadata()){
            (new ParkingSpace)->addSpacesOnLotCreation($query->insert_id,$numberOfSpaces);
            return true;
        }

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

    public function changeNumberOfSpaces(int $lot_id, int $number_of_spaces) : bool
    {
        /*
         * Salīdzināt atšķirību starp vietu daudzumu iepriekš un tagad, un attiecīgi izdzēst/pielikt vietas
         * stāvlaukumam, atbilstoši arī atjaunojot ParkingSpaces tabulu
         * */
        $lot = $this->getLot($lot_id);
        $difference = $number_of_spaces - $lot->space_count;
        if($difference > 0){
            (new ParkingSpace())->addSpacesOnSpaceCountEdit($lot_id,$number_of_spaces,$lot->space_count);
            return $this->updateNumberOfSpaces($lot_id,$number_of_spaces);
        }
        //Nevajag izdzēst arī reservations vietām, jo ir uzlikts datubāzē, kad izdzēš reservations, ja izdzēšas space
        (new ParkingSpace())->removeSpacesOnSpaceCountEdit($lot_id,$number_of_spaces);
        return $this->updateNumberOfSpaces($lot_id,$number_of_spaces);
    }

    public function updateNumberOfSpaces(int $lot_id, int $number_of_spaces) : bool
    {
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