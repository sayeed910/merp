<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class EntityMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:entity {name} {--m|migration} {--o|model} {--c|controller} {--d|repository} {--r|resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Entity for the application';

    protected $type = 'Entity';
    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->getNameInput();

        if (parent::fire() === false) {
            return;
        }

        if ($this->option('model')){
            $this->call('make:model', [
                'name' => 'App\Data\Models\\' . $name
            ]);
        }

        if ($this->option('migration')) {
            $table = Str::plural(Str::snake(class_basename($this->argument('name'))));

            $this->call('make:migration', [
                'name' => "create_{$table}_table",
                '--create' => $table,
            ]);
        }

        if ($this->option('controller')) {
            $controller = Str::studly(class_basename($this->argument('name')));

            $this->call('make:controller', [
                'name' => "{$controller}Controller",
                '--resource' => $this->option('resource'),
            ]);
        }

        if ($this->option('repository')){
            $this->call('make:repository', ['entity' => $name]);
        }

    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . "/../stubs/entity.stub";
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'o', InputOption::VALUE_NONE, 'Create a new model file for the model.'],

            ['repository', 'd', InputOption::VALUE_NONE, 'Create a new repository file for the model.'],

            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model.'],

            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model.'],

            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
        ];
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "\Domain";
    }
}
