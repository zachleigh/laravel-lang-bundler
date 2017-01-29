<?php

namespace LaravelLangBundler\Commands;

class MakeBundlesFolder extends LaravelLangBundlerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'langb:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get started by making a bundles directory.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setUp();

        $directory = resource_path('lang/bundles');

        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory);
        }

        $this->info('Bundles folder successfully created!');
    }
}
