<?php


namespace Afterflow\Framework\Concerns;


/**
 * Trait WorksWithJsonFiles
 * @package Afterflow\Framework\Concerns
 */
trait WorksWithJsonFiles
{
    /**
     * @param  null  $filename
     * @return mixed
     */
    public function read($filename = null)
    {
        $filename = $filename ?? $this->filename;
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * @param  array  $c
     * @param  null  $filename
     */
    public function write(array $c, $filename = null)
    {
        $filename = $filename ?? $this->filename;
        file_put_contents($filename, json_encode($c, JSON_PRETTY_PRINT));
    }

}