<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\config;

use phpDocumentor\Reflection\Types\Self_;

class Database
{
private static ?\PDO $pdo = null;

public  static  function getConnection(string $env = "test"): \PDO{
    if(self::$pdo == null){
        //create new pdo
        require_once __DIR__ . '/../../config/database.php';
        $config = getDatabaseConfig();
        self::$pdo = new \PDO(
            $config['database'][$env]['url'],
            $config['database'][$env]['username'],
            $config['database'][$env]['password']
        );
    }
        return self::$pdo;
    }

    public static function beginTranstaction(){
        self::$pdo->beginTransaction();
    }

    public static function commitTranstaction(){
        self::$pdo->commit();
    }

    public static function roolbackTranstaction(){
        self::$pdo->roolBack();
    }

}