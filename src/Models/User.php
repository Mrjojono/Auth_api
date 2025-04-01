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

    public function getManga()
    {
        $data = array(); // Tableau pour stocker les données des mangas
        $nb = 10; // Nombre de mangas à récupérer

        // Boucle pour récupérer les mangas
        for ($i = 1; $i <= $nb; $i++) {
            // Construire l'URL de l'API pour chaque manga en fonction de l'ID
            $jikanurl = "https://api.jikan.moe/v4/random/manga";

            // Faire la requête à l'API
            $response = file_get_contents($jikanurl);

            // Vérifier si la réponse est valide et non vide
            if ($response !== FALSE) {
                $mangaData = json_decode($response, true);

                // Vérifier si l'objet manga existe et n'est pas null
                if (isset($mangaData['data']) && $mangaData['data'] !== null) {
                    // Ajouter les données du manga au tableau $data
                    $data[] = $mangaData['data']; // Utilise 'data' pour extraire les informations pertinentes
                } else {
                    // Si pas de données valides, on continue avec l'ID suivant
                    echo "Aucune donnée pour l'ID {$i}\n";
                }
            } else {
                echo "Erreur lors de l'appel à l'API pour l'ID {$i}\n";
            }
        }

        // Vérifier si des mangas ont été récupérés
        if (!empty($data)) {
            // Retourner les données des mangas au format JSON
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Aucun manga trouvé']);
        }

        return $data; // Retourner le tableau des mangas
    }

    public function getAnime($pages)

    {
        if ($pages == 0) {
            $url = "https://animeapi.skin/trending?limit=10";
        } else {
            $url = "https://animeapi.skin/new?page=".$pages;
        }

        $response = file_get_contents($url);

        if ($response !== FALSE) {
            $animeData = json_decode($response, true);
            return $animeData;
        } else {
            echo "Erreur lors de l'appel à l'API ";
        }
    }

    
    public function getMovies($pages)
    {
        $apiKey = "3b6f8803063889e5d99e433272aad4d0";

        if ($pages == 0) {
            $url = "https://api.themoviedb.org/3/movie/popular?api_key=3b6f8803063889e5d99e433272aad4d0&language=fr-FR&page=10";
        } else {
            $url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=fr-FR&page=" . $pages;
        }

        $response = file_get_contents($url);

        if ($response !== FALSE) {
            $animeData = json_decode($response, true);
            return $animeData;
        } else {
            echo "Erreur lors de l'appel à l'API ";
        }
    }




    public function getEpisodes($title)
    {
        $url = "https://animeapi.skin/episodes?title=" . urlencode($title);;
        $response = file_get_contents($url);

        if ($response !== FALSE) {
            $episodesData = json_decode($response, true);
            return $episodesData;
        } else {
            echo "Erreur lors de l'appel à l'API ";
        }
    }

    public function getRandom()
    {

        $jikanurl = "https://api.jikan.moe/v4/characters?page=1&limit=20";
        $response = file_get_contents($jikanurl);
        if ($response !== FALSE) {
            $mangaData = json_decode($response, true);
            if (isset($mangaData['data']) && $mangaData['data'] !== null) {
                $data = $mangaData['data'];
            } else {
                echo "Aucune donnée";
            }
        } else {
            echo "Erreur lors de l'appel à l'API ";
        }

        return $data;
    }
}
