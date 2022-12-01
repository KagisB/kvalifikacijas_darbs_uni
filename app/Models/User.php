<?php

namespace app\Models;

use App\Models\DBConnection;

class User{
    // Tiek pieņemts, ka lietotāja datus ir jau pārbaudījis UserController, un tie ir fine
    public function addUser(string $username, string $password, string $email) : bool
    {
        /*
         * Izveidot lietotāju ar iedotajiem datiem, paroli šifrēt šeit vai vēl atsevišķā funkcijā?
         * Vajag gan jau uztaisīt, lai aizsūta verification email
         * */
        $password_hash = password_hash($password,PASSWORD_BCRYPT);

        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('INSERT INTO Users VALUES(?,?,?)');
        $query->bind_param('sss', $username, $password_hash, $email);
        $query->execute();
        if($query->result_metadata()){
            $connection->close();
            return true;
        }
        $connection->close();
        return false;

    }

    public function userExists(int $user_id) : bool
    {
        /*
         * Pārbaudīt, vai lietotājs eksistē
         * */
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('SELECT id FROM Users WHERE id = ?');
        $query->bind_param('s', $user_id);
        $query->execute();
        $query->bind_result($id);
        $connection->close();
        if($id) return true;
        return false;
    }

    public function checkLoginInfo(int $user_id, string $username, string $password) : bool
    {
        /*
         * Salīdzina padoto paroli ar datubāzē esošo paroli
         * */
        if(!$this->userExists($user_id))
        {
            return false;
        }
        $connection = (new DBConnection())->createConnection();
        $query = $connection->prepare('SELECT username, password FROM Users WHERE id = ? LIMIT 1');
        $query->bind_param('s', $user_id);
        $query->execute();
        $result = $query->get_result();
        while($row = $result->fetch_assoc())
        {
            if(password_verify($password,$row['password']) && $username === $row['username']){
                return true;
            }
        }
        $connection->close();
        return false;
    }
}
