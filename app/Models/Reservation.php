<?php

namespace app\Models;

use App\Models\DBConnection;
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
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('INSERT INTO Reservations VALUES (?,?,?,?,?)');
        $code = $this->generateRandomString();
        $timeFrom = date('Y-m-d H:i:s',$from->getTimestamp());
        $timeTill = date('Y-m-d H:i:s',$till->getTimestamp());
        $query->bind_param('iisss', $user_id, $space_id, $timeFrom, $timeTill, $code);
        $query->execute();
        $connection->close();
        return $query;
    }

    public function getReservationsByUserId(int $user_id) : array
    {
        /*
         * atgriezt visas rezervācijas noteiktā periodā? kuras rezervējis konkrēts lietotājs
         * */
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('SELECT id FROM Reservations WHERE user_id = ?');
        $query->bind_param('i', $user_id);
        $query->execute();
        $connection->close();
        $result = $query->get_result();
        $reservationIds= null;
        while($row = $result->fetch_assoc())
        {
            $reservationIds[] = $row['id'];
        }
        $connection->close();
        return $reservationIds;
    }

    public function getReservationsInTimePeriod(int $space_id,Datetime $from, Datetime $till) : array
    {
        /*
         * Iegūst rezervācijas noteiktajā periodā noteiktai stāvvietai
         * */
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare(
            'SELECT id FROM Reservations WHERE space_id = ?
                    AND from > ? AND till < ?');
        $timeFrom = date('Y-m-d H:i:s',$from->getTimestamp());
        $timeTill = date('Y-m-d H:i:s',$till->getTimestamp());
        $query->bind_param('iss', $space_id, $timeFrom, $timeTill);
        $query->execute();
        $connection->close();
        $result = $query->get_result();
        $reservationIds= null;
        while($row = $result->fetch_assoc())
        {
            $reservationIds[] = $row['id'];
        }
        $connection->close();
        return $reservationIds;
    }

    public function checkUserReservation(int $user_id,Datetime $from, Datetime $till) : bool
    {
        /*
         * Pārbaudīt, vai lietotājam ir rezervācija noteiktajā laika periodā
         * */
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare(
            'SELECT id FROM Reservations WHERE user_id = ?
                    AND from > ? AND till < ?');
        $timeFrom = date('Y-m-d H:i:s',$from->getTimestamp());
        $timeTill = date('Y-m-d H:i:s',$till->getTimestamp());
        $query->bind_param('iss', $space_id, $timeFrom, $timeTill);
        $query->execute();
        $connection->close();
    }

    public function checkSpaceReservation(int $space_id, Datetime $timeNow) : bool
    {
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare(
            'SELECT id FROM Reservations WHERE space_id = ?
                    AND from > ? AND till < ?');
        $timeNow = date('Y-m-d H:i:s',$timeNow->getTimestamp());
        $query->bind_param('is', $space_id, $timeNow);
        $query->execute();
        $connection->close();
        if($query->result_metadata())
        {
            return true;
        }
        return false;
    }

    public function generateRandomString($length = 7) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            if($i===3)
            {
                $randomString .= '-';
                continue;
            }
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}