<?php

namespace app\Models;

class ParkingLot{
    private int $id;
    private int $space_count;
    private string $address;

    public function __construct()
    {
        $this->id = 0;
        $this->address = '';
        $this->space_count = 0;
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
}