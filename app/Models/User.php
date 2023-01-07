<?php

namespace App\Models;

require_once "../../vendor/autoload.php";

use App\Models\DBConnection as DBConnection;

class User{
    private int $userId;
    private string $username;
    private int $status;
    private string $email;

    public function __construct(?int $id){
        $this->userId = $id;
        if($id && $id > -1){
            $data = $this->getUserInfo($this->userId);
            $this->username = $data['username'];
            $this->status = $data['status'];
            $this->email = $data['email'];
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getStatus()
    {
        return $this->status;
    }

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

    public function emailExists(string $email) :bool
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT id FROM Users WHERE email = ? LIMIT 1');
        $query->bind_param('s', $email);
        $query->execute();
        $result = $query->get_result();
        $connection->close();
        if($result){
            return true;
        }
        return false;
    }

    public function usernameExists(string $username) :bool
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT id FROM Users WHERE username = ? LIMIT 1');
        $query->bind_param('s', $username);
        $query->execute();
        $result = $query->get_result();
        $connection->close();
        if($result){
            return true;
        }
        return false;
    }

    public function getUserInfo(int $id) {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT username,status,email FROM Users WHERE id = ? LIMIT 1');
        $query->bind_param('i', $id);
        $query->execute();
        $info = null;
        $result = $query->get_result();
        $connection->close();
        while($row = $result->fetch_assoc()) {
            $info = [
                'id' => $id,
                'email' => $row['email'],
                'username' => $row['username'],
                'status' => $row['status'],
            ];
        }
        return $info;
    }

    public function checkPassword(int $id, string $password) : bool
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT password FROM Users WHERE id = ? LIMIT 1');
        $query->bind_param('i', $id);
        $query->execute();
        $info = null;
        $result = $query->get_result();
        $connection->close();
        while($row = $result->fetch_assoc()) {
            if(password_verify($password,$row['password'])){
                return false;
            }
        }
        return true;
    }

    public function getUserIdByUsername(string $username) : ?int
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('SELECT id FROM Users WHERE username = ? LIMIT 1');
        $query->bind_param('s', $username);
        $query->execute();
        $id = null;
        $result = $query->get_result();
        $connection->close();
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
        $result = $query->get_result();
        $connection->close();
        if($result->fetch_assoc()) return true;
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

    public function editUsername(string $username, int $userId) :bool
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('UPDATE Users SET username = ?  WHERE id= ?');
        $query->bind_param('si', $username, $userId);
        $query->execute();
        $connection->close();
        if($query->result_metadata()){
            return true;
        }
        return false;
    }

    public function editUserEmail(string $email, int $userId) :bool
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('UPDATE Users SET  email = ? WHERE id= ?');
        $query->bind_param('si',  $email, $userId);
        $query->execute();
        $connection->close();
        if($query->result_metadata()){
            return true;
        }
        return false;
    }

    public function editUserPassword(string $password, int $userId) : bool
    {
        $password_hash = password_hash($password,PASSWORD_BCRYPT);
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('UPDATE Users SET password = ? WHERE id= ?');
        $query->bind_param('si', $password_hash, $userId);
        $query->execute();
        $connection->close();
        if($query->result_metadata()){
            return true;
        }
        return false;
    }

    public function removeUser(int $userId) : bool
    {
        $connection = (new DBConnection())->createMySQLiConnection();
        $query = $connection->prepare('DELETE FROM Users WHERE id = ?');
        $query->bind_param('i', $userId);
        $query->execute();
        $connection->close();
        if($query->result_metadata()){
            return true;
        }
        return false;
    }
}
