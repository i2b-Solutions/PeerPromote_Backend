<?php
namespace Data\Services;

use Data\Dependencies\DependencyManager;

class UserServices
{
    public static function queryUsernameData(string $username)
    {
        $db = DependencyManager::getDatabase();
        $query = "SELECT * FROM Users WHERE Username=:username";
        
        $result = $db->query($query, ['username' => $username])->fetch();
        return $result;
    }
}