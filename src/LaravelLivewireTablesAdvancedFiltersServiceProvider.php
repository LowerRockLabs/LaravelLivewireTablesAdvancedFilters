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

            $this->publishes([
                __DIR__ . '/../resources/views/components/tools/filters/datePicker.blade.php' => resource_path('views/vendor/livewiretablesadvancedfilters/components/tools/filters/datePicker.blade.php'),
            ], 'livewire-tables-advanced-filters-views-datePickerFilter');

            $this->publishes([
                __DIR__ . '/../resources/views/components/tools/filters/dateRange.blade.php' => resource_path('views/vendor/livewiretablesadvancedfilters/components/tools/filters/dateRange.blade.php'),
            ], 'livewire-tables-advanced-filters-views-dateRangeFilter');

            $this->publishes([
                __DIR__ . '/../resources/views/components/tools/filters/numberRange.blade.php' => resource_path('views/vendor/livewiretablesadvancedfilters/components/tools/filters/numberRange.blade.php'),
            ], 'livewire-tables-advanced-filters-views-numberRangeFilter');

            $this->publishes([
                __DIR__ . '/../resources/views/components/tools/filters/slimSelect.blade.php' => resource_path('views/vendor/livewiretablesadvancedfilters/components/tools/filters/slimSelect.blade.php'),
            ], 'livewire-tables-advanced-filters-views-slimSelectFilter');

            $this->publishes([
                __DIR__ . '/../resources/views/components/tools/filters/smartSelect.blade.php' => resource_path('views/vendor/livewiretablesadvancedfilters/components/tools/filters/smartSelect.blade.php'),
            ], 'livewire-tables-advanced-filters-views-smartSelectFilter');
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
