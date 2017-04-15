<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model {
    protected static function getDatabaseConnection() {
        static $database = null;

        if ($database === null) {
//                This information is in the config file
//                $host = 'localhost';
//                $username = 'root';
//                $password = 'root';
//                $dbname = 'practice_database_mvc';
//
//                    This is replaced by information from the separate file
//                    $database = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",
//                        $username, $password);

            $dsn = 'mysql:host=' . Config::DATABASE_HOST . ';dbname=' . Config::DATABASE_NAME .
                ';charset-utf8';
            $database = new PDO($dsn, Config::DATABASE_USERNAME, Config::DATABASE_PASSWORD);

            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
    return $database;
    }
}