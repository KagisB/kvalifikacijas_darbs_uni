<?php

namespace App\Models;

require_once "../../vendor/autoload.php";

use App\Models\DBConnection as DBConnection;
use Cassandra\Date;
use Datetime;

class Reservation{
    public int $id;
    public int $user_id;
    public int $space_id;
    public string $from;
    public string $till;
    public string $reservation_code;

    public function __construct(?int $id = 0, ?int $user_id = 0, ?int $space_id = 0, ?string $from = "",
                                ?string $till = "", ?string $reservation_code = '')
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->space_id = $space_id;
        $this->from = $from;
        $this->till = $till;
        $this->reservation_code = $reservation_code;
    }

    public function addReservation(int $user_id, int $space_id, string $from, string $till) : bool
    {
        /*
         * Izveidot rezervāciju no padotajiem datiem
         * */
        if($this->checkSpaceReservationBeforeCreation($space_id,$from,$till)){
            return false;
        }
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('INSERT INTO Reservations (`user_id`,`space_id`,`from`,`till`,`reservation_code`) VALUES (?,?,?,?,?)');
        $code = $this->generateRandomString();
        //$timeFrom = date('Y-m-d H:i:s',strtotime($from));
        //$timeTill = date('Y-m-d H:i:s',strtotime($till));
        $query->bind_param('iisss', $user_id, $space_id, $from, $till, $code);
        $query->execute();
        $connection->close();
        if($query){
            return true;
        }
        return false;
    }

    public function getReservationIdsByUserId(int $user_id) : array
    {
        /*
         * atgriezt visas rezervācijas noteiktā periodā? kuras rezervējis konkrēts lietotājs
         * */
        $from = date('Y-m-d H:i:s');
        $till = new Datetime('now');
        $till->modify('+1 month');
        $connection = (new DBConnection())->createPDOConnection();
        $sql = <<<MySQL
            SELECT id FROM Reservations 
            WHERE user_id = :user_id
            AND (`from` < :from AND till < :timeTill)
            OR (`from` > :from AND till < :timeTill)
            OR (`from` > :from AND till > :timeTill)
        MySQL;
        $timeTill = date('Y-m-d H:i:s',$till->getTimestamp());
        $params = [
            'user_id' => $user_id,
            'from' => $from,
            'timeTill' => $timeTill,
        ];
        $query = $connection->prepare($sql);
        $query->execute($params);
        $connection=null;
        $reservationIds = null;
        while($row = $query->fetch()){
            $reservationIds[] = $row['id'];
        }
        return $reservationIds;
    }

    public function getReservationsByUserId(int $user_id) : array
    {
        $reservationIds = $this->getReservationIdsByUserId($user_id);
        $reservations = [];
        foreach($reservationIds as $reservationId) {
            $reservation = $this->getReservationById($reservationId);
            $reservations[] = [
              'id' => $reservation->id,
              'user_id' => $reservation->user_id,
              'space_id' => $reservation->space_id,
              'from' => $reservation->from,
              'till' => $reservation->till,
              'reservation_code' => $reservation->reservation_code,
            ];
        }
        return $reservations;
    }

    public function getReservationById(int $id)
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT * FROM Reservations WHERE id = ?');
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        $connection->close();
        $row = $result->fetch_assoc();
        return  new Reservation($id,$row['user_id'],$row['space_id'],$row['from'],$row['till'], $row['reservation_code']);
    }

    public function getSpaceReservationsInTimePeriod(int $space_id,string $from, string $till) : ?array
    {
        $connection = (new DBConnection())->createPDOConnection();
        $sql = <<<MySQL
            SELECT * FROM Reservations 
            WHERE space_id = :space_id
            AND (`from` < :timeFrom AND till < :timeTill)
            OR (`from` > :timeFrom AND till < :timeTill)
            OR (`from` > :timeFrom AND till > :timeTill)
        MySQL;
        /*$timeFrom = date('Y-m-d H:i:s',$from->getTimestamp());
        $timeTill = date('Y-m-d H:i:s',$till->getTimestamp());*/
        $params = [
            'space_id' => $space_id,
            'timeFrom' => $from,
            'timeTill' => $till,
        ];
        $query = $connection->prepare($sql);
        $query->execute($params);

        $reservations = [];
        while($row = $query->fetch()){
            $reservations[]=[
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'space_id' => $row['space_id'],
                'from' => $row['from'],
                'till' => $row['till'],
                'reservation_code' => $row['reservation_code'],
            ];
        }
        $connection=null;
        return $reservations;
    }

    public function checkUserReservation(int $user_id,Datetime $from, Datetime $till) : bool
    {
        /*
         * Pārbaudīt, vai lietotājam ir rezervācija noteiktajā laika periodā
         * */
        if(is_null($this->getReservationIdsByUserId($user_id,$from,$till))){
            return false;
        }
        return true;
    }

    public function checkSpaceReservation(int $space_id, string $timeNow) : bool
    {
        $connection = (new DBConnection())->createPDOConnection();
        $sql = <<<MySQL
            SELECT id FROM Reservations 
            WHERE space_id = :space_id
            AND (`from` < :timeNow AND 'till' > :timeNow)
        MySQL;
        //$timeNow = date('Y-m-d H:i:s',$timeNow->getTimestamp());
        $params = [
            'space_id' => $space_id,
            'timeNow' => $timeNow,
        ];
        $query = $connection->prepare($sql);
        $query->execute($params);
        $results = $query->fetchAll();
        $connection=null;
        if(empty($results)){
            return false;
        }
        return true;
    }

    public function checkSpaceReservationBeforeCreation(int $space_id, string $from, string $till) :bool
    {
        $connection = (new DBConnection())->createPDOConnection();
        $sql = <<<MySQL
            SELECT id FROM Reservations 
            WHERE space_id = :space_id
            AND (`from` <= :from AND 'till' <= :till)
            OR (`from` >= :from AND 'till' <= :till)
            OR (`from` >= :from AND 'till' >= :till)
        MySQL;
        $params = [
            'space_id' => $space_id,
            'from' => $from,
            'till' => $till,
        ];
        $query = $connection->prepare($sql);
        $query->execute($params);
        $results = $query->fetchAll();
        $connection=null;
        if(empty($results)){
            return false;
        }
        return true;
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