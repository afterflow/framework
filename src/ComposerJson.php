<?php

namespace Afterflow\Framework;


use Afterflow\Framework\Concerns\WorksWithJsonFiles;

/**
 * Class ComposerJson
 * @package Afterflow\Framework
 */
class ComposerJson
{
    use WorksWithJsonFiles;

    /**
     * @var string
     */
    protected $filename = 'composer.json';

    /**
     * @param $files
     */
    public function addAutoloadFiles($files)
    {
        $files = is_array($files) ? $files : [$files];
        $c = $this->read();

        $c['autoload'] = isset($c['autoload']) ? $c['autoload'] : [];
        $c['autoload']['files'] = isset($c['autoload']['files']) ? $c['autoload']['files'] : [];
        $c['autoload']['files'] = collect($c['autoload']['files'])->merge($files)->unique()->toArray();

        $this->write($c);
    }

}