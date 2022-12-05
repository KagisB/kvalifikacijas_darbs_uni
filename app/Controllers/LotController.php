<?php

namespace app\Controllers;

use Datetime;
class LotController{
    public function getBusynessData(Datetime $from, Datetime $till)
    {
        /*
         * Iegūt sarakstu ar aizņemtību noteiktajā periodā, un sagatavot datus lapai
         * */
    }

    public function getSpaces()
    {
        /*
         * Iegūt sarakstu ar stāvvietām, kas pieder stāvlaukumam, sagatavot datus lapai
         * */
    }

    public function addLot()
    {
        /*
         * Pārbaudīt datus, padot modeļa metodei, kura uztaisītu jaunu stāvlaukumu
         * */
    }

    public function editLot()
    {
        /*
         * Nomainīt informāciju par stāvlaukumu, vai vietu skaitu izmainīt, vai nomainīt maksas rādītāju
         * */
    }

    public function removeLot()
    {
        /*
         * izdzēst visas stāvvietas, kas saistītas ar stāvlaukumu, tad izdzēst pašu stāvlaukumu
         * */
    }
}
