<?php

namespace neilherbertuk\celcatwebapi\Facades;

use Illuminate\Support\Facades\Facade;

class CelcatWebAPI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'CelcatWebAPI';
    }
}