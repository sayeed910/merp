<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class EloquentRepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:erepository {entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Eloquent Repository for the given entity';

    protected $type = 'EloquentRepository';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
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
        return __DIR__ . "/../stubs/repository.eloquent.stub";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Data\Repositories';
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
        return 'Eloquent'.trim($this->argument('entity')) . 'Repository';
    }
}
