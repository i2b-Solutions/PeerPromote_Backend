<?php
namespace Domain\Controllers;

use Data\Entities\IsAvailable;
use Data\Entities\ResponseData;
use Domain\UseCases\UserUseCases;

class UserController
{
    public static function isUsernameAvailable(string $username): ResponseData
    {
        try {
            return new ResponseData(
                new IsAvailable(UserUseCases::isUsernameAvailableUseCase($username)),
                true,
                ""
            );
        } catch (\Throwable $th) {
            return new ResponseData(
                null,
                false,
                $th->getMessage()
            );
        }
    }

    public static function isEmailAvailable(string $email): ResponseData
    {
        try {
            return new ResponseData(
                new IsAvailable(UserUseCases::isEmailAvailableUseCase($email)),
                true,
                ""
            );
        } catch (\Throwable $th) {
            return new ResponseData(
                null,
                false,
                $th->getMessage()
            );
        }
    }
}
