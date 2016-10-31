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

    /**
     * @test
     */
    public function make_new_bundle_file_makes_new_file()
    {
        $file = resource_path('lang/bundles/test/components/new.php');

        $this->assertFileNotExists($file);

        Artisan::call('langb:new', ['path' => 'test.components.new']);

        $this->assertFileExists($file);
    }

    /**
     * @test
     */
    public function make_new_bundle_file_doesnt_overwrite_folders()
    {
        Artisan::call('langb:new', ['path' => 'test.new']);

        Artisan::call('langb:new', ['path' => 'test.components.new']);

        Artisan::call('langb:new', ['path' => 'test.newer']);

        Artisan::call('langb:new', ['path' => 'new']);

        $files = [
            resource_path('lang/bundles/test/new.php'),
            resource_path('lang/bundles/test/components/new.php'),
            resource_path('lang/bundles/test/newer.php'),
            resource_path('lang/bundles/new.php'),
        ];

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }
    }
}
