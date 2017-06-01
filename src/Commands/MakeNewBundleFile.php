<?php

namespace LaravelLangBundler\Commands;

class MakeNewBundleFile extends LaravelLangBundlerCommand
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
        $this->setUp();

        $pathArray = collect(explode('.', $this->argument('path')));

        $filename = $pathArray->pop().'.php';

        $pathArray->prepend('bundles');

        $basePath = resource_path('lang'.DIRECTORY_SEPARATOR);

        $path = $this->buildPath($pathArray->all(), $basePath);

        $this->createFile($path, $filename);

        $this->info('Bundle file successfully created!');
    }

    /**
     * Create file from stub.
     *
     * @param string $path
     * @param string $filename
     */
    protected function createFile($path, $filename)
    {
        $filePath = $path.$filename;

        $stub = __DIR__.DIRECTORY_SEPARATOR.'bundle-file-stub.php';

        if (!$this->filesystem->exists($filePath)) {
            $this->filesystem->copy($stub, $filePath);
        }
    }
}
