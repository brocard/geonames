<?php

namespace BrocardJr\Geo\Facades;

use Illuminate\Support\Facades\Facade;

class GeoServices extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geoservices';
    }
}
