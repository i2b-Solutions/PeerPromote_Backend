<?php
namespace Data\Dependencies;
use Medoo\Medoo;

class DatabaseDependency
{
    // Constants for the DB connection
    const DB_HOST = "208.109.40.16";
    const DB_NAME = "i2b_solutions";
    const DB_USERNAME = "adminpalma";
    const DB_PASSWORD = "4dm1nP4lm4";
    const DB_CHARSET = "utf8";

    // Create Medoo instance
    public static function createInstance(): Medoo
    {
        return new Medoo([
            'database_type' => 'mysql',
            'database_name' => self::DB_NAME,
            'server' => self::DB_HOST,
            'username' => self::DB_USERNAME,
            'password' => self::DB_PASSWORD,
            'charset' => self::DB_CHARSET
        ]);
    }
}
