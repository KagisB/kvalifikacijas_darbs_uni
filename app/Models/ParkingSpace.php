<?php
/*
 * Šeit iegūs datus no datubāzes un mainīs datubāzes datus.
 * */
namespace app\Models;

use app\Models\Reservation;

class ParkingSpace{
    private int $id;
    public int $number;
    private int $lot_id;

    //public function __construct(int $lot_id, ?int $id=0, ?int $number=0, ?int $hourly_rate=0){
    public function __construct(int $lot_id, ?int $id=0, ?int $number=0)
    {
        $this->id = $id;
        $this->number= $number;
        $this->lot_id = $lot_id;
    }

    public function getSpaceIdsFromLotId(int $lot_id) : array
    {
        /*
         * do query in database where it returns all space ids that belong to the lot
         * */
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('SELECT id FROM ParkingSpace WHERE id = ?');
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

    public function isReserved() : bool
    {
        /*
         * no datubāzes, ja ir rezervēts laika periodā, atgriezt true
         * Iesaistīt Reservation tabulu/modeli
         * */
        $timeNow = new \DateTime('now');
        return (new Reservation)->checkSpaceReservation($timeNow);
    }

    public function addSpace(int $lot_id, int $number) : bool
    {
        /*
         * ielikt datubāzē jaunu parking space(protams, ja dati ir validēti kā derīgi)
         * */
        $connection = (new DBConnection())->createConnection();
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
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('SELECT * FROM ParkingSpace WHERE id = ?');
        $query->bind_param('i', $space_id);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_all();
        if($row){
            return new ParkingSpace($row['id'],$row['number'],$row['lot_id']);
        }
        return null;
    }

    public function addSpacesOnLotCreation(int $lot_id, int $number_of_spaces)
    {
        /*
         * No padotā stāvlaukuma id un vietu skaita izveido noteikto skaitu stāvvietu, kuras
         * saistītas ar padoto stāvlaukumu
         * */
        $connection = (new DBConnection())->createConnection();
        for($i = 1; $i <=$number_of_spaces ; $i++){
            $query = $connection->prepare('INSERT INTO ParkingSpaces VALUES (?,?)');
            $query->bind_param('ii', $lot_id, $i);
            $query->execute();
        }
        $connection->close();

    }
}
