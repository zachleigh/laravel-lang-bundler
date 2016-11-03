<?php

namespace LaravelLangBundler\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

class MakeBundleMod extends Command
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
        $filesystem = new Filesystem();

        $pathArray = ['LangBundler', 'Mods'];

        $path = app_path().'/';

        foreach ($pathArray as $directory) {
            $path .= $directory.'/';

            if (!$filesystem->exists($path)) {
                $filesystem->makeDirectory($path);
            }
        }

        $name = ucfirst($this->argument('name')).'Mod';

        $stub = $this->getStub($name, $filesystem);

        $filesystem->put(
            app_path(implode('/', $pathArray).'/'.$name.'.php'),
            $stub
        );

        $this->info('New bundle modification file successfully created!');
    }

    /**
     * Get stub and fill.
     *
     * @param string      $name
     * @param Filestystem $filesystem
     *
     * @return string
     */
    protected function getStub($name, $filesystem)
    {
        $namespace = Container::getInstance()->getNamespace();

        $namespace = $namespace.'LangBundler\\Mods';

        $stub = $filesystem->get(__DIR__.'/../BundleItems/ModStub.php');

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
