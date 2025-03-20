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
    
    public function getAllbyId($id): array|bool
    {

        $sql = $this->conn->prepare("select * from users where IDUSER = :id");
        try {
            $sql->execute(
                [
                    ":id" => $id
                ]
            );
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: false;
        } catch (Exception $e) {
            return false;
        }
    }



    public function create($email, $nom, $password): bool
    {
        
        //password_hash($password, PASSWORD_DEFAULT);
        $sql = $this->conn->prepare('INSERT into users(NOM,EMAIL,PASSWORD) values(:name,:email,:password)');
        try {
            $sql->execute([
                ":name" => $nom,
                ":email" => $email,
                ":password" => $password
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function findByEmail($email): array|false
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE EMAIL = :email');
        try {
            $stmt->execute([':email' => $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $result ?: false; // ✅ Retourne un tableau ou false si l'utilisateur n'est pas trouvé
        } catch (Exception $e) {
            error_log("Erreur lors de la recherche de l'utilisateur : " . $e->getMessage());
            return false; // ✅ Retourne false en cas d'erreur
        }
    }
    


}

?>