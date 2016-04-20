<?php

namespace Twine\Raven\Facades;

use Twine\Raven\Client;
use Illuminate\Support\Facades\Facade;

class Raven extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
