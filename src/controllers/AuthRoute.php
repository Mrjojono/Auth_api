<?php

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use App\Middlewares\AuthMiddleware;
use App\database;
use App\Models\User;
//use App\Middlewares\AuthMiddleware;

$app->group('/auth', function (RouteCollectorProxy $group) {
    
    $group->post('/register', function (Request $request, Response $response)
    {
        $db = new database();
        $data = $request->getParsedBody();
        $nom = $data['name'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        // Enregistrer l'utilisateur dans la base de données
        $user = new User($db);
        $user->create($nom, $email, $password);

        $response->getBody()->write(json_encode(['message' => 'User ajouter avec succès']));
        return $response->withHeader('Content-Type', 'application/json');
    });


    $group->post('/login', function(Request $request, Response $response){
       $db =  new database();
        $data = $request->getParsedBody();
        $email = $data['email'];
        $password = $data['password'];

        
        $user = new User($db);
        $userData = $user->findByEmail($email);
        var_dump($userData);
        var_dump($password);

        var_dump($email);

        if($userData && password_verify($password, $userData['PASSWORD'])){
            $payload=[
                'iss' => 'your-issuer',
                'sub' => $userData['IDUSER'],
                'iat' => time(),
                'exp' => time() + 3600, 
            ];
            
            $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
            $response->getBody()->write(json_encode(['token' => $jwt]));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
            return $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    });

    /**
     * function hello de test  
     */
    
    $group->get('/hello', function(Request $request, Response $response)
    {
        $response->getBody()->write("hello word");
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/users', function(Request $request, Response $response)
    {
        $db = new database();
        $user = new User($db);
        $users = $user->getAll();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    });


    $group->get('/profile/{id}',function (Request $request, Response $response, array $args)
    {
        $id = $args['id']; 
        $userId = $request->getAttribute('userId');
        $response->getBody()->write(json_encode(['authenticatedUserId' => $userId]));
        return $response->withHeader('Content-Type', 'application/json');
    })->add(new AuthMiddleware());
 
});

