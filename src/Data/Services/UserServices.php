<?php
namespace Data\Services;
use Data\Dependencies\DependencyManager;

class UserServices
{
    public static function queryUsernameData()
    {
        $db = DependencyManager::getDatabase();
    }
}