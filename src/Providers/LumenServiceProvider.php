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
        if (empty($this->app['config']['raven.dsn'])) {
            return;
        }

        $path = $this->getPath();

        $this->app->configure('raven');
        $this->mergeConfigFrom($path, 'raven');

        $handler = $this->getHandler();

        $this->app['log']->pushHandler($handler);
    }
}
