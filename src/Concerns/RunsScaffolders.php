<?php


namespace Afterflow\Framework\Concerns;


/**
 * Trait RunsScaffolders
 * @package Afterflow\Framework\Concerns
 */
trait RunsScaffolders
{
    /**
     * @param $classes
     * @return array
     */
    public function runScaffolders($classes)
    {
        $returns = [];
        foreach ($classes as $class) {
            $returns [$class] = $this->runScaffolder($class);
        }
        return $returns;
    }

    /**
     * @param $class
     * @return mixed
     */
    public function runScaffolder($class)
    {
        $recipe = new $class(app('afterflow-scaffold-command'));
        return $recipe->run();
    }

}