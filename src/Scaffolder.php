<?php


namespace Afterflow\Framework;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
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

    static $description = '';

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

    public function replaceInFileIfNotAlready($file, $find, $replace, $substringToCheck = null)
    {
        $file = file_get_contents($file);
        $substringToCheck = $substringToCheck ?? $replace;

        if (strpos($file, $substringToCheck)) {
            return false;
        }

        file_put_contents($file, str_replace($find, $replace, file_get_contents($file)));
    }

    public function replaceInFiles($files, $find, $replace)
    {
        foreach ($files as $file) {
            $this->replaceInFile($file, $find, $replace);
        }
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

    public function config($key, $value = null)
    {
        $filename = $_SERVER['HOME'].'/.config/afterflow/config.json';
        if (!file_exists($filename)) {
            File::makeDirectory(dirname($filename), 0666, true);
            File::put($filename, '{}');
        }

        $config = json_decode(File::get($filename), true);

        if ($value) {
            $config[$key] = $value;
            File::put($filename, json_encode($config, JSON_PRETTY_PRINT));
        }

        return $config[$key] ?? null;
    }

}