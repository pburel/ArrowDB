<?php namespace Claymm\ArrowDB\Facades;

use Illuminate\Support\Facades\Facade;

class ArrowDB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'arrowdb';
    }
}
