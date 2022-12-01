<?php
/*
 * Šeit iegūs datus no datubāzes un mainīs datubāzes datus.
 * */
namespace app\Models;

class ParkingSpace{
    private int $id;
    public int $number;
    private int $lot_id;
    private int $hourly_rate;

    //public function __construct(int $lot_id, ?int $id=0, ?int $number=0, ?int $hourly_rate=0){
    public function __construct()
    {
        /*$this->id = $id;
        $this->number= $number;
        $this->hourly_rate = $hourly_rate;
        $this->lot_id = $lot_id;*/
    }

    public function getSpaceIdsFromLotId(int $lot_id) : ?array
    {
        /*
         * do query in database where it returns all space ids that belong to the lot
         * */
    }

    public function isReserved() : bool
    {
        /*
         * no datubāzes, ja ir rezervēts laika periodā, atgriezt true
         * */
    }

    public function addSpace(int $lot_id, int $number) : bool
    {
        /*
         * ielikt datubāzē jaunu parking space(protams, ja dati ir validēti kā derīgi)
         * */
    }

    public function getSpace(int $id) : ?ParkingSpace
    {
        /*
         * Iegūst informāciju par stāvvietu, ja tāda stāvvieta eksistē
         * */
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
