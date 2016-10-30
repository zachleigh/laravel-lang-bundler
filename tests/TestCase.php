<?php

namespace LaravelLangBundler\tests;

use Artisan;
use LaravelLangBundler\Bundler;
use LaravelLangBundler\Translator;
use Illuminate\Filesystem\Filesystem;
use LaravelLangBundler\ServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as IlluminateTestCase;

class TestCase extends IlluminateTestCase
{
    /**
     * Bundler instance.
     *
     * @var Bundler
     */
    protected $bundler;


    /**
     * Translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->register(ServiceProvider::class);

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Setup DB and test variables before each test.
     */
    protected function setUp()
    {
        $filesystem = new Filesystem();

        $configDir = __DIR__.'./../src/config.php';

        $configTarget = __DIR__.'./../vendor/laravel/laravel/config/lang-bundler.php';

        $filesystem->copy($configDir, $configTarget);

        parent::setUp();

        $this->bundler = new Bundler();

        $this->translator = new Translator();

        $filesystem->makeDirectory(resource_path('lang'));
    }

    /**
     * Teardown after each class.
     */
    protected function tearDown()
    {
        $filesystem = new Filesystem();

        $filesystem->deleteDirectory(resource_path('lang'));

        $filesystem->delete(config_path('lang-bundler.php'));

        parent::tearDown();
    }

    /**
     * Copy stubs to vendor Laravel app lang directory.
     *
     * @param string|array $stubs
     */
    protected function copyStubs($stubs)
    {
        if (!is_array($stubs)) {
            $stubs = [$stubs];
        }

        $filesystem = new Filesystem();

        $filesystem->makeDirectory(resource_path('lang/bundles/'));

        foreach ($stubs as $stub) {
            $stubPath = __DIR__.'/stubs/'.$stub;

            if (is_dir($stubPath)) {
                $filesystem->copyDirectory(
                    $stubPath,
                    resource_path('lang/bundles/'.$stub)
                );
            } else {
                $target = resource_path('lang/bundles/'.$stub.'.php');

                $stubPath = $stubPath.'.php';

                if (file_exists($stubPath)) {
                    $filesystem->copy($stubPath, $target);
                }
            }
        }
    }

    /**
     * Copy translation files into vendor Laravel app.
     */
    protected function copyTranslations()
    {
        $filesystem = new Filesystem();

        $filesystem->makeDirectory(resource_path('lang/en/'));

        $filesystem->makeDirectory(resource_path('lang/ja/'));

        $translations = [
            'en' => __DIR__.'/stubs/en/',
            'ja' => __DIR__.'/stubs/ja/'
        ];

        foreach ($translations as $name => $translation) {
            $target = resource_path('lang/'.$name);

            $filesystem->copyDirectory($translation, $target);
        }
    }
}
