<?php

namespace Twine\Raven\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Twine\Raven\Client;
use Twine\Raven\Logger;

abstract class AbstractServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app[Client::class] = $this->app->share(function ($app) {
            return new Client($app['config']['raven'], $app['queue'], $app->environment());
        });

        $this->registerLogger();
    }

    /**
     * Get path to config.
     *
     * @return string
     */
    protected function getPath()
    {
        return realpath(__DIR__.'/../config/config.php');
    }

    /**
     * Get Raven Monolog handler.
     *
     * @return Monolog\Handler\RavenHandler
     */
    protected function getHandler()
    {
        $handler = new RavenHandler($this->app[Client::class], $this->app['config']['raven.level']);
        $handler->setFormatter(new LineFormatter('%message%'));
        
        // Add processors
        $processors = $this->app['config']['raven.monolog.processors'] ?: [];

        if (is_array($processors)) {
            foreach ($processors as $process) {
                // Get callable
                if (is_callable($process)) {
                    $callable = $process;
                } elseif (is_string($process)) {
                    $callable = new $process();
                } else {
                    throw new \Exception('Raven: Invalid processor');
                }

                // Add processor to Raven handler
                $handler->pushProcessor($callable);
            }
        }

        return $handler;
    }
}
