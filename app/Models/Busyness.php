<?php

namespace app\Models;

use Datetime;
class Busyness{
    public function addPeriods() // Maybe run this every day? To update the table
    {
        /*
         * every day run this method to add 24 periods of busyness, checking all reservations for that
         * time period for that specific lot, and seeing how many spaces are taken from the total?
         *
         * */
    }

    public function getPeriods(Datetime $from, Datetime $till) : array
    {
        /*
         * iegūt aizņemtības periodus, padot kā id array
         * */
    }
}