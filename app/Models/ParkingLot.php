<?php

namespace app\Models;

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

    public function addLot(int $numberOfSpaces)
    {
        /*
         * pievienot stāvlaukumu datubāzē, kad stāvlaukums izveidots, izveidot stāvvietas caur
         * ParkingSpace model
         * */
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