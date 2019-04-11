<?php

namespace Lebenlabs\SimpleStorage\Facades;

use Illuminate\Support\Facades\Facade;

class SimpleStorageService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'simplestorageservice';
    }
}
