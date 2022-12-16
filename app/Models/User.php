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
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('INSERT INTO Users VALUES(?,?,?)');
        $query->bind_param('sss', $username, $password_hash, $email);
        $query->execute();
        $connection->close();
        if($query->result_metadata()){
            return true;
        }
        return false;
    }

    public function getUserInfo(int $id) {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT username,status FROM Users WHERE id = ? LIMIT 1');
        $query->bind_param('i', $id);
        $query->execute();
        $connection->close();
        $info = null;
        $result = $query->get_result();
        while($row = $result->fetch_assoc()) {
            $info = [
                'id' => $id,
                'username' => $row['username'],
                'status' => $row['status'],
            ];
        }
        return $info;
    }

    public function getUserIdByUsername(string $username) : ?int
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT id FROM Users WHERE username = ? LIMIT 1');
        $query->bind_param('s', $username);
        $query->execute();
        $connection->close();
        $id = null;
        $result = $query->get_result();
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
        }
        return $id;
    }
    public function userExists(int $user_id) : bool
    {
        /*
         * Pārbaudīt, vai lietotājs eksistē
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT id FROM Users WHERE id = ?');
        $query->bind_param('i', $user_id);
        $query->execute();
        $query->bind_result($id);
        $connection->close();
        if($id) return true;
        return false;
    }

    public function checkLoginInfo(string $username, string $password) : bool
    {
        /*
         * Salīdzina padoto paroli ar datubāzē esošo paroli
         * */
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT username, password FROM Users WHERE username = ? LIMIT 1');
        $query->bind_param('s', $username);
        $query->execute();
        $result = $query->get_result();
        while($row = $result->fetch_assoc())
        {
            if(password_verify($password,$row['password'])){
                return true;
            }
        }
        $connection->close();
        return false;
    }
}
