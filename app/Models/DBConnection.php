<?php

namespace app\Models;

use mysqli;
class DBConnection
{
    private const DB_HOSTNAME = 'db';
    private const DB_USERNAME = 'admin';
    private const DB_PASSWORD = 'password';
    private const DB_DATABASE = 'carPark';
    private const DB_PORT = 3306;

    public function createConnection() : mysqli
    {
        return mysqli_connect(self::DB_HOSTNAME,self::DB_USERNAME,
            self::DB_PASSWORD, self::DB_DATABASE,self::DB_PORT);
    }
}