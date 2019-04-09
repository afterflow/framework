<?php


namespace Afterflow\Framework;


use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class Scaffolder
 * @package Afterflow\Framework
 */
abstract class Scaffolder
{
    /**
     * @var Command
     */
    protected $command;
    /**
     * @var
     */
    protected $question;

    /**
     * Scaffolder constructor.
     * @param  null  $command
     */
    public function __construct($command = null)
    {
        $this->command = $command ?? app('afterflow-scaffold-command');
        $this->composer = new ComposerJson();
        $this->package = new PackageJson();
    }

    /**
     * @return mixed
     */
    public function run()
    {
        if ($this->shouldRun()) {
            return $this->handle();
        }
    }

    /**
     * @return bool
     */
    protected function shouldRun()
    {
        if ($this->question) {
            return $this->command->confirm($this->question, true);
        }
        return true;
    }

    /**
     * @return mixed
     */
    abstract function handle();

    /**
     * @param $file
     * @param $find
     * @param $replace
     */
    public function replaceInFile($file, $find, $replace)
    {
        file_put_contents($file, str_replace($find, $replace, file_get_contents($file)));
    }

    /**
     * @param $dir
     * @param $from
     * @param  null  $to
     * @return mixed
     */
    public function copyStub($dir, $from, $to = null)
    {
        $to = $to ?? $from;

        if (is_dir($dir.'/'.$from)) {
            return \File::copyDirectory($dir.'/'.$from, $to);
        }
        return \File::copy($dir.'/'.$from, $to);
    }

    /**
     * @param $tasks
     * @param  null  $comment
     */
    public function exec($tasks, $comment = null)
    {
        $tasks = is_array($tasks) ? $tasks : [$tasks];
        foreach ($tasks as $task) {
            $this->command->task($comment.$task, function () use ($task) {
                (new Process($task))->run();
            });
        }

    }

}