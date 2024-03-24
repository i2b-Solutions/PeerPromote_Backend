<?php
namespace Data\Services;

use Data\Dependencies\DependencyManager;

class UserServices
{
    private static function queryUserDataByField(string $field, string $value)
    {
        $db = DependencyManager::getDatabase();
        $query = "SELECT * FROM Users WHERE $field=:$field";
        return $db->query($query, [$field => $value])->fetch();
    }

    public static function queryUsernameData(string $username)
    {
        return self::queryUserDataByField('Username', $username);
    }

    public static function queryEmailData(string $email)
    {
        return self::queryUserDataByField('Email', $email);
    }
}