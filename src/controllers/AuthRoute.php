<?php

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\database;
use App\Models\User;

$app->group('/auth', function (RouteCollectorProxy $group) {

    $group->post('/register', function (Request $request, Response $response) {
        $db = new database();
        $data = $request->getParsedBody();
        $nom = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        // Enregistrer l'utilisateur dans la base de données
        $user = new User($db);
        $user->create($email, $nom, $password);

        $response->getBody()->write(json_encode(['message' => 'User account created!']));
        return $response->withHeader('Content-Type', 'application/json');
    });


    $group->post('/login', function (Request $request, Response $response) {
        $db = new database();
        $data = $request->getParsedBody();
        $email = $data['email'];
        $password = $data['password'];

        $user = new User($db);
        $userData = $user->findByEmail($email);

        if (is_array($userData)) { // ✅ Vérifie si les données existent et sont sous forme de tableau
            if (password_verify($password, $userData['PASSWORD'])) {
                session_start();
             $_SESSION['user_id'] = $userData['IDUSER'];
             

            

                $response->getBody()->write(json_encode([
                    'message' => 'Login successful',
                    'token' =>  $_SESSION['user_id']
                ]));
            } else {
                $response->getBody()->write(json_encode(['error' => 'Mot de passe incorrect']));
                return $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(['error' => 'Utilisateur non trouve']));
            return $response->withStatus(404);
        }

        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * @param Request $request
     * @param Response $response
     * recupere l'id utilisateur pour retourner ces infos 
     */
    $group->get('/profile', function (Request $request, Response $response) {
    session_start(); 
    $_SESSION['user_id'] = 20;
        if (!isset($_SESSION['user_id'])) {
            $response->getBody()->write(json_encode(['error' => 'Vous devez vous connecter pour accéder à cette ressource']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    
        //$id = $_SESSION['user_id'];
        $db = new database();
        $user = new User($db);
        $users = $user->getAllbyId(20);
    
        if ($users === false) {
            $response->getBody()->write(json_encode(['error' => 'Utilisateur non trouvé']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    });
    

/**
 * function qui detruit la session lors de la deconnexion 
 */
    $group->get('/logout', function (Request $request, Response $response) {
        session_start();
        session_destroy();
        $response->getBody()->write(json_encode(['message' => 'Logout successful']));
        return $response->withHeader('Content-Type', 'application/json');
    });
    /**
     * function hello de test  
     */

    $group->get('/hello', function (Request $request, Response $response) {
        $response->getBody()->write("hello word");
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/users', function (Request $request, Response $response) {
        $db = new database();
        $user = new User($db);
        $users = $user->getAll();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    });


   /* $group->get('/profile/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];
        $userId = $request->getAttribute('userId');
        $response->getBody()->write(json_encode(['authenticatedUserId' => $userId]));
        return $response->withHeader('Content-Type', 'application/json');
    });
*/
});