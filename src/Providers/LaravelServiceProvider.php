<?php

namespace Twine\Raven\Providers;

class LaravelServiceProvider extends AbstractServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $path = $this->getPath();

        $this->publishes([$path => config_path('raven.php')], 'config');
        $this->mergeConfigFrom($path, 'raven');

        if (empty($this->app['config']['raven.dsn'])) {
            return;
        }

        $handler = $this->getHandler();

        $this->app['log']->getMonolog()->pushHandler($handler);
    }
}
