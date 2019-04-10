<?php

namespace Afterflow\Framework\Console\Commands;

use Afterflow\Framework\Concerns\RunsScaffolders;
use Afterflow\Framework\ScaffolderRegistry;
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

//        foreach (app()->tagged('afterflow-scaffolder') as $s) {
//            $scafs [get_class($s)] = $s;
//        }

        ScaffolderRegistry::scan();

        $scafs = collect(ScaffolderRegistry::all())
            ->flatten();

        $scafs2 = $scafs->map(function ($v) {
            return '<comment>'.$v.'</comment> - '.$v::$description;
        });


        $scaf = $this->choice('Which scaffolder to use?', $scafs2->toArray());

        $this->runScaffolder($scafs [$scafs2->flip()[$scaf]]);


        $this->line('');
        $fire = '🔥';
        $this->line('    '.$fire.'   Scaffolding Done!   '.$fire);
        $this->line('');

    }

}