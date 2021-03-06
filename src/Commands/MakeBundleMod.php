<?php

namespace LaravelLangBundler\Commands;

use Illuminate\Container\Container;

class MakeBundleMod extends LaravelLangBundlerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'langb:mod {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a custom modification file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setUp();

        $pathArray = ['LangBundler', 'Mods'];

        $basePath = app_path().DIRECTORY_SEPARATOR;

        $this->buildPath($pathArray, $basePath);

        $name = ucfirst($this->argument('name')).'Mod';

        $stub = $this->getStub($name);

        $this->filesystem->put(
            app_path(implode(DIRECTORY_SEPARATOR, $pathArray).DIRECTORY_SEPARATOR.$name.'.php'),
            $stub
        );

        $this->info('New bundle modification file successfully created!');
    }

    /**
     * Get stub and fill.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        $namespace = Container::getInstance()->getNamespace();

        $namespace = $namespace.'LangBundler\\Mods';

        $stub = $this->filesystem->get(
            dirname(__DIR__).DIRECTORY_SEPARATOR.'BundleItems'.DIRECTORY_SEPARATOR.'ModStub.php');

        $stub = str_replace(
            'LaravelLangBundler\BundleItems',
            $namespace,
            $stub
        );

        $stub = str_replace(
            'ModStub',
            $name,
            $stub
        );

        return $stub;
    }
}
