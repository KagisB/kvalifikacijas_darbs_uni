<?php

namespace app\Models;

use Datetime;

class Reservation{

    public function __construct()
    {

    }

    public function addReservation(int $user_id, int $space_id, Datetime $from, Datetime $till) : bool
    {
        /*
         * Izveidot rezervāciju no padotajiem datiem
         * */
    }

    public function getReservationsByUserId(int $user_id) : array
    {
        /*
         * atgriezt visas rezervācijas noteiktā periodā? kuras rezervējis konkrēts lietotājs
         * */
    }

    public function getReservationsInTimePeriod(int $space_id,Datetime $from, Datetime $till) : array
    {
        /*
         * Iegūst rezervācijas noteiktajā periodā noteiktai stāvvietai
         * */
    }

}