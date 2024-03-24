<?php
namespace Data\Dependencies;

use Medoo\Medoo;

class DependencyManager
{
    private static Medoo $databaseInstance;

    public static function getDatabase(): Medoo
    {
        if (!isset (self::$databaseInstance)) {
            self::$databaseInstance = DatabaseDependency::createInstance();
        }

        return self::$databaseInstance;
    }
}