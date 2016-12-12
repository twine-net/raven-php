<?php

namespace Twine\Raven\Providers;

use Illuminate\Log\Writer;

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

    /**
     * Register the logger instance in the container.
     *
     * @return void
     */
    protected function registerLogger()
    {
        $this->app->instance('log', new Writer(
            $this->getLogger(),
            $this->app['log']->getEventDispatcher()
        ));
    }
}
