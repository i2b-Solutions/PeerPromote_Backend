<?php
namespace Data\Entities\UserEntities;

class RegisterUserResponseData
{
    public int $userId;
    public string $username;

    public function __construct(int $userId, string $username)
    {
        $this->UserID = $userId;
        $this->Username = $username;
    }
}