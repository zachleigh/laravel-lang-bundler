<?php

namespace LaravelLangBundler;

use LaravelLangBundler\Commands\MakeBundlesFolder;
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
                new Bundler(),
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

        $this->commands('command.langb.start');
    }
}
