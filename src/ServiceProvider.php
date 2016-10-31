<?php

namespace LaravelLangBundler;

use LaravelLangBundler\Commands\MakeBundlesFolder;
use LaravelLangBundler\Commands\MakeNewBundleFile;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LangBundler::class, function ($app) {
            return new LangBundler(
                new BundleMap(),
                new Translator()
            );
        });

        $this->registerCommands();
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('lang-bundler.php'),
        ], 'config');
    }

    /**
     * Register Artisan commands.
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.langb.start', function ($app) {
            return $app[MakeBundlesFolder::class];
        });

        $this->app->singleton('command.langb.new', function ($app) {
            return $app[MakeNewBundleFile::class];
        });

        $this->commands('command.langb.start');

        $this->commands('command.langb.new');
    }
}
