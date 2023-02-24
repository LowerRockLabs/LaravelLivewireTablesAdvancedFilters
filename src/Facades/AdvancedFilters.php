<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LowerRockLabs\LaravelLivewireTablesAdvancedFilters\AdvancedFilters
 */
class AdvancedFilters extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'advancedfilters';
    }
}
