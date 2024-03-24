<?php

declare(strict_types=1);

use Data\Entities\ResponseData;
use Domain\Constants\DomainConstants;
use Domain\Controllers\UserController;
use Domain\Helpers\DomainHelpers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/user', function (Group $group) {
        $group->post('/check_username', function (Request $request, Response $response) {
            $postData = $request->getParsedBody();
            $username = $postData['username'] ?? null;

            if (empty ($username)) {
                $responseData = DomainHelpers::createEmptyParamsResponse();
            } else {
                $responseData = UserController::isUsernameAvailable($username);
            }

            $jsonResponse = json_encode($responseData);
            $response->getBody()->write($jsonResponse);

            return $response->withHeader('Content-Type', 'application/json');
        });

        $group->post('/check_email', function (Request $request, Response $response) {
            $postData = $request->getParsedBody();
            $email = $postData['email'] ?? null;

            if (empty ($email)) {
                $responseData = DomainHelpers::createEmptyParamsResponse();
            } else {
                $responseData = UserController::isEmailAvailable($email);
            }

            $jsonResponse = json_encode($responseData);
            $response->getBody()->write($jsonResponse);

            return $response->withHeader('Content-Type', 'application/json');
        });
    });
};
