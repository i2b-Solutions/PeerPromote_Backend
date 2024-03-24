<?php
namespace Domain\Controllers;

use Data\Entities\IsAvailable;
use Data\Entities\ResponseData;
use Domain\Helpers\DomainHelpers;
use Domain\UseCases\UserUseCases;

class UserController
{
    public static function isUsernameAvailable(string $username): ResponseData
    {
        return DomainHelpers::createResponseWithError(function () use ($username) {
            return new IsAvailable(UserUseCases::isUsernameAvailableUseCase($username));
        });
    }

    public static function isEmailAvailable(string $email): ResponseData
    {
        return DomainHelpers::createResponseWithError(function () use ($email) {
            return new IsAvailable(UserUseCases::isEmailAvailableUseCase($email));
        });
    }
}
