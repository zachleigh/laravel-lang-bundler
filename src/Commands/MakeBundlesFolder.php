<?php

namespace LaravelLangBundler\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeBundlesFolder extends Command
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
        $filesystem = new Filesystem();

        $directory = resource_path('lang/bundles');

        if (!$filesystem->exists($directory)) {
            $filesystem->makeDirectory($directory);
        }

        $this->info('Rules file successfully created!');
    }
}
