<?php
namespace Domain\UseCases;

use Data\Entities\UserEntities\RegisterUserRequestData;
use Data\Entities\UserEntities\RegisterUserResponseData;
use Data\Services\UserServices;

class UserUseCases
{
    public static function isUsernameAvailableUseCase(string $username): bool
    {
        $userData = UserServices::queryUsernameData($username);
        return ($userData === false);
    }

    public static function isEmailAvailableUseCase(string $email): bool
    {
        $userData = UserServices::queryEmailData($email);
        return ($userData === false);
    }

    public static function registerUserUseCase(RegisterUserRequestData $user): RegisterUserResponseData
    {
        return UserServices::queryRegisterUserData($user);
    }
}
