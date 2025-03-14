<?php
namespace App\Models;
use Exception;
use PDO;
use App\database;

class User
{

    private $conn;

    public function __construct(private database $db)
    {
        $this->conn = $this->db->getConnection();
    }

    public function getAll(): array|bool
    {

        $sql = $this->conn->prepare("select * from users");
        try {
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }


    public function create($email, $nom, $password): bool
    {
        $hashedPassword = $password;
        //password_hash($password, PASSWORD_DEFAULT);
        $sql = $this->conn->prepare('INSERT into users(NOM,EMAIL,PASSWORD) values(:name,:email,:password)');
        try {
            $sql->execute([
                ":name" => $nom,
                ":email" => $email,
                ":password" => $hashedPassword
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function findByEmail($email): array|bool
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email ');
        try {
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    
}


?>