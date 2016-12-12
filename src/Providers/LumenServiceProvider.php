<?php

namespace Twine\Raven\Providers;

use Psr\Log\LoggerInterface;
use Twine\Raven\Logger;

class LumenServiceProvider extends AbstractServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $path = $this->getPath();

        $this->app->configure('raven');
        $this->mergeConfigFrom($path, 'raven');

        if (empty($this->app['config']['raven.dsn'])) {
            return;
        }

        $handler = $this->getHandler();

        $this->app['log']->pushHandler($handler);
    }

    /**
     * Register the logger instance in the container.
     *
     * @return void
     */
    protected function registerLogger()
    {
        $logger = new Logger(
            $this->app['log']->getName(),
            $this->app['log']->getHandlers(),
            $this->app['log']->getProcessors()
        );

        $this->app->singleton(LoggerInterface::class, function () use ($logger) {
            return $logger;
        });

        $this->app->instance('log', $logger);
    }
}
