<?php

namespace Twine\Raven\Providers;

use Illuminate\Log\Writer;
use Twine\Raven\Logger;

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
        $logger = new Logger(
            $this->app['log']->getMonolog()->getName(),
            $this->app['log']->getMonolog()->getHandlers(),
            $this->app['log']->getMonolog()->getProcessors()
        );

        $this->app->instance('log', new Writer(
            $logger,
            $this->app['log']->getEventDispatcher()
        ));
    }
}
