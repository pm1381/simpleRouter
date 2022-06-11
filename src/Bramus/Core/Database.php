<?php

namespace Bramus\Core;

class Database
{
    public static $connection;

    public function connect()
    {
        if (! self::$connection) {
            $dsn = "mysql:host=" . 'localhost' . ";dbname=". 'restapi' .";charset=UTF8";
            try {
                self::$connection = new \PDO($dsn, 'root', '');
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    public function connectionStatus()
    {
        return self::$connection;
    }
}
