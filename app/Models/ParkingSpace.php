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

    public function __construct(){
        $this->id =0;
        $this->number=0;
        $this->hourly_rate=0;
        $this->lot_id=0;
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

    public function addSpace(int $lot_id, int $number, int $hourly_rate) : bool
    {
        /*
         * ielikt datubāzē jaunu parking space(protams, ja dati ir validēti kā derīgi)
         * */
    }

}
