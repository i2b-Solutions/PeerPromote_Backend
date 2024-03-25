<?php
namespace Domain\Controllers;

use Data\Entities\IsAvailable;
use Data\Entities\ResponseData;
use Data\Entities\UserEntities\RegisterUserRequestData;
use Data\Entities\UserEntities\RegisterUserResponseData;
use Domain\Helpers\DomainHelpers;
use Domain\UseCases\UserUseCases;

class UserController
{
    public static function isUsernameAvailable(string $username): IsAvailable
    {
        return new IsAvailable(UserUseCases::isUsernameAvailableUseCase($username));

    }

    public static function isEmailAvailable(string $email): IsAvailable
    {
        return new IsAvailable(UserUseCases::isEmailAvailableUseCase($email));
    }

    public static function registerUser(RegisterUserRequestData $user): RegisterUserResponseData
    {
        return UserUseCases::registerUserUseCase($user);
    }
}
