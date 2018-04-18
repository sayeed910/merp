<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Repository for the given entity';

    protected $type = 'Repository';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    public function fire()
    {
        if (parent::fire() === false) return;

        $this->call('make:erepository', ['entity' => $this->argument('entity')]);
    }


    protected function buildClass($name)
    {
        $entity = $this->argument('entity');
        $replace = [
            'dummyVar' => lcfirst($entity),
            'DummyEntity' => $entity
            ];

        return str_replace(
        array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . "/../stubs/repository.stub";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Domain';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [

        ];
    }

    protected function getNameInput()
    {
        return trim($this->argument('entity')) . 'Repository';
    }
}
