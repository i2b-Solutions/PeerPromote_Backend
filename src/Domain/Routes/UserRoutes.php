<?php
namespace Domain\Routes;

use Domain\Controllers\UserController;
use Domain\Helpers\DomainHelpers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

class UserRoutes
{
    public static function initializeUserRoutes(App $app)
    {
        $app->group('/user', function (Group $group) {
            $group->post('/check_username', function (Request $request, Response $response) {
                $postData = $request->getParsedBody();
                $username = $postData['username'] ?? null;

                $isEmptyResponse = DomainHelpers::generateEmptyResponse([$username]);

                if ($isEmptyResponse = DomainHelpers::generateEmptyResponse([$username])) {
                    return DomainHelpers::createJsonResponse($isEmptyResponse, $response);
                }

                return DomainHelpers::createJsonResponse(UserController::isUsernameAvailable($username), $response);
            });

            $group->post('/check_email', function (Request $request, Response $response) {
                $postData = $request->getParsedBody();
                $email = $postData['email'] ?? null;

                if ($isEmptyResponse = DomainHelpers::generateEmptyResponse([$email])) {
                    return DomainHelpers::createJsonResponse($isEmptyResponse, $response);
                }

                return DomainHelpers::createJsonResponse(UserController::isEmailAvailable($email), $response);
            });
        });
    }
}