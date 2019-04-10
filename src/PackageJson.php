<?php

namespace Afterflow\Framework;

use Afterflow\Framework\Concerns\WorksWithJsonFiles;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class PackageJson
 * @package Afterflow\Framework
 */
class PackageJson
{
    use WorksWithJsonFiles;

    /**
     * @var string
     */
    protected $filename = 'package.json';

    /**
     * @param  array  $deps
     */
    public function removeDevDependencies(array $deps)
    {
        $packageJson = $this->read();

        foreach ($deps as $dep) {
            unset($packageJson['devDependencies'][$dep]);
        }

        $this->write($packageJson);
    }

    public function hasPackages($packages)
    {
        $c = $this->read();

        $require = collect(Arr::get($c, 'dependencies', []))->merge(Arr::get($c, 'devDependencies', []))->keys();
        foreach ($packages as $package) {
            $package = strpos($package, '@') ? Str::before($package, '@') : $package;
            if (!$require->contains($package)) {
                return false;
            }
        }

        return true;
    }

}