<?php

namespace LaravelLangBundler\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeNewBundleFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'langb:new {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new empty bundle file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filesystem = new Filesystem();

        $pathArray = collect(explode('.', $this->argument('path')));

        $filename = $pathArray->pop().'.php';

        $path = $this->buildDirectoryStructure($pathArray, $filesystem);

        $this->createFile($path, $filename, $filesystem);

        $this->info('Bundle file successfully created!');
    }

    /**
     * Create directories included in path if they don't exist.
     *
     * @param Collection $pathArray
     * @param filesystem $filesystem
     *
     * @return string
     */
    protected function buildDirectoryStructure($pathArray, $filesystem)
    {
        $pathArray->prepend('bundles');

        $path = resource_path('lang/');

        foreach ($pathArray as $directory) {
            $path .= $directory.'/';

            if (!$filesystem->exists($path)) {
                $filesystem->makeDirectory($path);
            }
        }

        return $path;
    }

    /**
     * Create file from stub.
     *
     * @param string     $path
     * @param string     $filename
     * @param Filesystem $filesystem
     */
    protected function createFile($path, $filename, $filesystem)
    {
        $filePath = $path.$filename;

        $stub = __DIR__.'/bundle-file-stub.php';

        if (!$filesystem->exists($filePath)) {
            $filesystem->copy($stub, $filePath);
        }
    }
}
