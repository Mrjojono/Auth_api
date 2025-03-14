<?php 
declare(strict_types=1);
namespace App;
use PDO;
class database{
   
    public function __construct(

    )
    {
       
    }

    public function getConnection(): PDO {
        $pdo = new PDO($_ENV['DB_DSN'],$_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        return $pdo;
    }
}

?>