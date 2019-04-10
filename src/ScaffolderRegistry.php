<?php


namespace Afterflow\Framework;


class ScaffolderRegistry
{
    public static $scaffolders = [];

    public static function register($provider, $scaffolders)
    {
        self::$scaffolders[$provider] = self::$scaffolders[$provider] ?? [];
        $scaffolders = is_array($scaffolders) ? $scaffolders : [$scaffolders];
        self::$scaffolders[$provider] = collect(self::$scaffolders[$provider])->merge($scaffolders)->unique()->toArray();
    }

    public static function all()
    {
        return self::$scaffolders;
    }

    public static function scan()
    {
        collect(get_declared_classes())->filter(function ($class) {
            return is_subclass_of($class, ScaffolderDiscovery::class);
        })->mapWithKeys(function ($v, $k) {

            self::register($v, $v::scaffolders());

            return [$v => $v::scaffolders()];
        });
    }
}