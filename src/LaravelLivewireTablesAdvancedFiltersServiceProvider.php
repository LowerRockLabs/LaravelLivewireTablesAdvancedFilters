<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use Illuminate\Support\ServiceProvider;

class LaravelLivewireTablesAdvancedFiltersServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lrlAdvancedTableFilters');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lrlAdvancedTableFilters');

        if ($this->app->runningInConsole()) {
            $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lrlAdvancedTableFilters');

            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/lowerrocklabs/livewiretablesadvancedfilters'),
            ], 'livewire-tables-advanced-filters-lang');

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('livewiretablesadvancedfilters.php'),
            ], 'livewire-tables-advanced-filters-config');

            $this->publishes([
                __DIR__ . '/../resources/css' => public_path('vendor/lowerrocklabs/livewiretablesadvancedfilters'),
            ], 'livewire-tables-advanced-filters-css');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/lowerrocklabs/livewiretablesadvancedfilters'),
            ], 'livewire-tables-advanced-filters-views');
        }
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'livewiretablesadvancedfilters');
    }
}
