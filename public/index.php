<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


$app = AppFactory::create();


$app->addBodyParsingMiddleware();


require __DIR__ . '/../src/controllers/AuthRoute.php';

// Démarrer l'application
$app->run();