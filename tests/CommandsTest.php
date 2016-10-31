<?php

namespace LaravelLangBundler\tests;

use Artisan;

class CommandsTest extends TestCase
{
    /**
     * @test
     */
    public function make_bundles_folder_command_makes_a_bundles_folder()
    {
        $directory = resource_path('lang/bundles');

        $this->assertFileNotExists($directory);

        Artisan::call('langb:start');

        $this->assertFileExists($directory);
    }
}
