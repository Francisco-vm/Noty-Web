<?php

namespace App\Config;

use PDO;
use PDOException;

class Database{
    private static ?self $instance = null;
    private PDO $pdo;
    private string $appEnv;

    private function __construct(){
    }
}