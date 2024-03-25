<?php
namespace Domain\Routes;

use Data\Entities\UserEntities\RegisterUserRequestData;
use Domain\Controllers\UserController;
use Domain\Helpers\DomainHelpers;
use Domain\Helpers\UserHelpers;
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

                if ($isEmptyResponse = DomainHelpers::generateEmptyResponseData([$username])) {
                    return DomainHelpers::createJsonResponse($isEmptyResponse, $response);
                }

                try {
                    $checkResult = UserController::isUsernameAvailable($username);
                    $responseData = DomainHelpers::generateOkResponseData($checkResult);
                    return DomainHelpers::createJsonResponse($responseData, $response);
                } catch (\Throwable $th) {
                    return DomainHelpers::generateErrorResponseData($th, $response);
                }
            });

            $group->post('/check_email', function (Request $request, Response $response) {
                $postData = $request->getParsedBody();
                $email = $postData['email'] ?? null;

                if ($isEmptyResponse = DomainHelpers::generateEmptyResponseData([$email])) {
                    return DomainHelpers::createJsonResponse($isEmptyResponse, $response);
                }

                try {
                    $checkResult = UserController::isEmailAvailable($email);
                    $responseData = DomainHelpers::generateOkResponseData($checkResult);
                    return DomainHelpers::createJsonResponse($responseData, $response);
                } catch (\Throwable $th) {
                    return DomainHelpers::generateErrorResponseData($th, $response);
                }
            });

            $group->post('/register', function (Request $request, Response $response) {
                $postData = $request->getParsedBody();
                $uploadedFiles = $request->getUploadedFiles();

                $selfieLocation = UserHelpers::generateSelfieLocation();

                $user = new RegisterUserRequestData(
                    $postData['user'] ?? null,
                    $postData['pass'] ?? null,
                    $postData['countryId'] ?? null,
                    $postData['isCompany'] ?? null,
                    $postData['cityId'] ?? null,
                    $postData['languages'] ?? null,
                    $postData['birthdate'] ?? null,
                    $postData['email'] ?? null,
                    $postData['area'] ?? null,
                    $postData['phone'] ?? null,
                    $uploadedFiles['selfie'] ?? null,
                    $selfieLocation
                );

                if ($isEmptyResponse = DomainHelpers::generateEmptyResponseData($user->toArray())) {
                    return DomainHelpers::createJsonResponse($isEmptyResponse, $response);
                }

                try {
                    $registerResult = UserController::registerUser($user);
                    $responseData = DomainHelpers::generateOkResponseData($registerResult);
                    $userId = $registerResult->userId;
                    UserHelpers::saveSelfie($selfieLocation, $userId, $user->selfie);
                    return DomainHelpers::createJsonResponse($responseData, $response);
                } catch (\Throwable $th) {
                    return DomainHelpers::generateErrorResponseData($th, $response);
                }
            });
        });
    }
}