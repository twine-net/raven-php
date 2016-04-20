<?php

namespace Twine\Raven\Providers;

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
}
