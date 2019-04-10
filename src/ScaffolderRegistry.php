<?php


namespace Afterflow\Framework;


class ScaffolderRegistry
{
    public static $scaffolders = [];

    public static function register(Scaffolder $s)
    {
        self::$scaffolders[$s] = $s;
    }

    public static function all()
    {
        return self::$scaffolders;
    }
}