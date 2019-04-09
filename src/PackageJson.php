<?php

namespace Afterflow\Framework;

use Afterflow\Framework\Concerns\WorksWithJsonFiles;

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

}