<?php

namespace Afterflow\Framework\Console\Commands;

use Afterflow\Framework\Concerns\RunsScaffolders;
use Illuminate\Console\Command;

class ScaffoldCommand extends Command
{
    use RunsScaffolders;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'afterflow:scaffold {class?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Apply scaffolding';


    public function handle()
    {
        app()->instance('afterflow-scaffold-command', $this);

        if ($s = $this->argument('class')) {
            return $this->runScaffolder($s);
        }

        $scafs = [];

        foreach (app()->tagged('afterflow-scaffolder') as $s) {
            $scafs [get_class($s)] = $s;
        }

        $scaf = $this->choice('Which scaffolder to use?', array_keys($scafs));
        $this->runScaffolder($scafs[$scaf]);


        $this->line('');
        $fire = '🔥';
        $this->line('    '.$fire.'   Scaffolding Done!   '.$fire);
        $this->line('');

    }

}