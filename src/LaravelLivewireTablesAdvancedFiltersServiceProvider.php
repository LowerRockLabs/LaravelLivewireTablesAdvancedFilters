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
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', '');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'livewiretablesadvancedfilters');
        if ($this->app->runningInConsole()) {
            $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'livewiretablesadvancedfilters');

            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/lowerrocklabs/livewiretablesadvancedfilters'),
            ], 'livewiretablesadvancedfilters-lang');

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('livewiretablesadvancedfilters.php'),
            ], 'livewiretablesadvancedfilters-config');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/lowerrocklabs/livewiretablesadvancedfilters'),
            ], 'livewiretablesadvancedfilters-css');

            
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/lowerrocklabs/livewiretablesadvancedfilters'),
            ], 'livewiretablesadvancedfilters-views');
        
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'livewiretablesadvancedfilters');
    }
}
