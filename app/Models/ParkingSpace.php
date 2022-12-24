<?php
/*
 * Šeit iegūs datus no datubāzes un mainīs datubāzes datus.
 * */
namespace App\Models;

require_once "../../vendor/autoload.php";

use App\Models\Reservation as Reservation;
use DateTime;

class ParkingSpace{
    public int $id;
    public int $number;
    public int $lot_id;
    public bool $reservation_status = false;

    //public function __construct(int $lot_id, ?int $id=0, ?int $number=0, ?int $hourly_rate=0){
    public function __construct(?int $lot_id=0, ?int $id=0, ?int $number=0)
    {
        $this->id = $id;
        $this->number= $number;
        $this->lot_id = $lot_id;
        $this->reservation_status = $this->isReserved($id);
    }

    public function spaceExists(int $id)
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT * FROM ParkingSpaces WHERE id = ?');
        $query->bind_param('i', $id);
        $query->execute();
        $connection->close();
        return $query;
    }
    public function getSpaceIdsFromLotId(int $lot_id) : ?array
    {
        /*
         * do query in database where it returns all space ids that belong to the lot
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT id FROM ParkingSpaces WHERE lot_id = ?'); // sort by number i guess?
        $query->bind_param('i', $lot_id);
        $query->execute();
        $result = $query->get_result();
        $spaceIds= null;
        while($row = $result->fetch_assoc())
        {
            $spaceIds[] = $row['id'];
        }
        $connection->close();
        return $spaceIds;
    }

    public function getSpaceFromIds(int $lot_id) : ?array
    {
        $spaces = null;
        $spaceIds = $this->getSpaceIdsFromLotId($lot_id);
        foreach($spaceIds as $spaceId)
        {
            $spaces[] = $this->getSpace($spaceId);
        }
        return $spaces;
    }

    public function isReserved(int $id) : bool
    {
        /*
         * no datubāzes, ja ir rezervēts laika periodā, atgriezt true
         * Iesaistīt Reservation tabulu/modeli
         * */
        $timeNow = new DateTime('now');
        return (new Reservation)->checkSpaceReservation($id,$timeNow);
    }

    public function addSpace(int $lot_id, int $number) : bool
    {
        /*
         * ielikt datubāzē jaunu parking space(protams, ja dati ir validēti kā derīgi)
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        for($i = 1; $i <=$number ; $i++){
            $query = $connection->prepare('INSERT INTO ParkingSpaces VALUES (?,?)');
            $query->bind_param('ii', $lot_id, $i);
            $query->execute();
        }
        $connection->close();
    }

    public function getSpace(int $space_id) : ?ParkingSpace
    {
        /*
         * Iegūst informāciju par stāvvietu, ja tāda stāvvieta eksistē
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT * FROM ParkingSpaces WHERE id = ?');
        $query->bind_param('i', $space_id);
        $query->execute();
        $result = $query->get_result();
        $connection->close();
        while($row = $result->fetch_assoc()) {
            return new ParkingSpace($row['lot_id'],$row['id'],$row['number']);
        }
        return null;
    }

    public function addSpacesOnLotCreation(int $lot_id, int $number_of_spaces)
    {
        /*
         * No padotā stāvlaukuma id un vietu skaita izveido noteikto skaitu stāvvietu, kuras
         * saistītas ar padoto stāvlaukumu
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        for($i = 1; $i <= $number_of_spaces; $i++){
            $query = $connection->prepare('INSERT INTO ParkingSpaces (lot_id,number) VALUES (?,?)');
            $query->bind_param('ii', $lot_id, $i);
            $query->execute();
        }
        $connection->close();
    }

    public function addSpacesOnSpaceCountEdit(int $lot_id, int $number_of_spaces, int $originalSpaceCount)
    {
        /*
         * No padotā stāvlaukuma id un vietu skaita izveido noteikto skaitu stāvvietu, kuras
         * saistītas ar padoto stāvlaukumu
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        for($i = $originalSpaceCount+1; $i <= $number_of_spaces; $i++){
            $query = $connection->prepare('INSERT INTO ParkingSpaces VALUES (?,?)');
            $query->bind_param('ii', $lot_id, $i);
            $query->execute();
        }
        $connection->close();
    }

    public function removeSpacesOnLotDeletion(int $lot_id)
    {
        /*
         * No padotā stāvlaukuma id un vietu skaita izveido noteikto skaitu stāvvietu, kuras
         * saistītas ar padoto stāvlaukumu
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('DELETE FROM ParkingSpaces WHERE lot_id = ?');
        $query->bind_param('i', $lot_id);
        $query->execute();
        $connection->close();
        return $query;
    }

    public function removeSpacesOnSpaceCountEdit(int $lot_id, int $number_of_spaces)
    {
        /*
         * No padotā stāvlaukuma id un vietu skaita izveido noteikto skaitu stāvvietu, kuras
         * saistītas ar padoto stāvlaukumu
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('DELETE FROM ParkingSpaces WHERE lot_id = ? AND number > ?');
        $query->bind_param('ii', $lot_id, $number_of_spaces);
        $query->execute();
        $connection->close();
        return $query;
    }
}
