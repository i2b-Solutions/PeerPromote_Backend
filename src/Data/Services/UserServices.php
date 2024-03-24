<?php
namespace Data\Services;

use Data\Dependencies\DependencyManager;

class UserServices
{
    private static function queryUserDataByField(string $fieldName, string $fieldValue)
    {
        $db = DependencyManager::getDatabase();
        $query = "SELECT * FROM Users WHERE $fieldName = :value";
        return $db->query($query, [':value' => $fieldValue])->fetch();
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