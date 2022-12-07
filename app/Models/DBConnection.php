<?php

namespace app\Models;

use mysqli;
use PDO;
use PDOException;

class DBConnection
{
    private const DB_HOSTNAME = 'db';
    private const DB_USERNAME = 'admin';
    private const DB_PASSWORD = 'password';
    private const DB_DATABASE = 'carPark';
    private const DB_PORT = 3306;

    public function createMySQLiConnection() : ?mysqli
    {
        return mysqli_connect(self::DB_HOSTNAME,self::DB_USERNAME,
            self::DB_PASSWORD, self::DB_DATABASE,self::DB_PORT);
    }

    public function createPDOConnection() : ?PDO
    {
        $conn = null;
        try {
             $conn = new PDO(
                 'mysql:host='.self::DB_HOSTNAME.';dbname='.self::DB_DATABASE.';charset=UTF8;port='.self::DB_PORT,
                    self::DB_USERNAME, self::DB_PASSWORD);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
    }
}