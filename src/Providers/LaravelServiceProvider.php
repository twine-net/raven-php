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
        if (empty($this->app['config']['raven.dsn'])) {
            return;
        }

        $path = $this->getPath();

        $this->publishes([$path => config_path('raven.php')], 'config');
        $this->mergeConfigFrom($path, 'raven');

        $handler = $this->getHandler();

        $this->app['log']->getMonolog()->pushHandler($handler);
    }
}
