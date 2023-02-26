# LaravelLivewireTablesAdvancedFilters
Advanced filters for Rappasoft Laravel Livewire Tables v2.0 and above

* Numeric Range Filter
* Date Range Filter
* Date Picker Filter
* Smart Select (Select2 Style)

# Installation
This package is available to be installed via Composer
```terminal
composer require lowerrocklabs/laravel-livewire-tables-advanced-filters
```

# The Filters

Filter classes should be in your table's head in the same way as existing filters.

## Numeric Range Filter
Filter with a configurable Minimum/Maximum value, provides two values to the filter() function
![Number Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/NumberRangeFilter.png)

Include the class after your namespace declaration
```php
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\NumberRangeFilter;
```

In the filters() function in your data table component:
```php
NumberRangeFilter::make('Age')
    ->options(
        [
            'min' => 10,
            'max' => 100
        ])
    ->filter(function (Builder $builder, array $numberRange) {
            $builder->where('age', '>=', $numberRange['min'])->where('age', '<=', $numberRange['max']);
    }),
```

### Dependencies
This depends on custom CSS, which can be included in your component by using the "numberRange.cssInclude" configuration option.
1. Used in-line, by setting numberRange.cssInclude to "inline" in the config file.
2. Included from a minified file, this requires publishing the CSS files, and setting numberRange.cssInclude to "include"
3. Included as part of your webpack/bundle, setting numberRange.cssInclude to "none".

### Configuration Options
The colours in use are fully customisable, designed for class-based "dark/light" themes approach, as are the default min/max options, see guidance below.  Any of these can be over-ridden on a per-filter basis using the "config()" option on the filter.
```php
    'numberRange' => [
        'defaults' => [
            'min' => 0, // A Default Minimum Value
            'max' => 100,  // A Default Maximum Value
        ],
        'styling' => [
            'light' => [ // Used When "dark" class is not in a parent element
                'activeColor' => '#FFFFF',
                'fillColor' => '#0366d6', // The color of the bar for the selected range
                'primaryColor' => '#0366d6', // The primary color
                'progressBackground' => '#eee', // The color of the remainder of the bar
                'thumbColor' => '#FFFFFF', // The color of the Circle
                'ticksColor' => 'silver',
                'valueBg' => 'transparent',
                'valueBgHover' => '#0366d6', // The bg color of the current value when the relevant selector is hovered over
            ],
            'dark' => [ // Used When "dark" class is in a parent element
                'activeColor' => 'transparent',
                'fillColor' => '#FF0000', // The color of the bar for the selected range
                'progressBackground' => '#eee', // The color of the remainder of the bar                
                'primaryColor' => '#00FF00', // The primary color
                'thumbColor' => '#0000FF', // The color of the Circle
                'ticksColor' => 'silver',
                'valueBg' => '#000000',                
                'valueBgHover' => '#000000', // The bg color of the current value when the relevant selector is hovered over
            ],
        ],
        /*
        How to Include the CSS file.  Options are:
        - inline - Pushes the <style></style> tags to the
        - include - Requires you to publish the CSS file, and will then include it
        - none - Do not include the CSS, note that you will need to include the CSS in your webpack.
        */
        'cssInclude' => 'inline',
    ],
```

## Date Range Filter
Flatpickr Filter with a configurable Minimum/Maximum value, provides two values to the filter() function () in the form of an array.

Include the class after your namespace declaration
```php
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DateRangeFilter;
```

In the filters() function in your data table component:
```php
DateRangeFilter::make('Created Date')
->filter(function (Builder $builder, array $dateRange) {
    $builder->where('created_at', '>=', $dateRange['min'])->where('created_at', '<=', $dateRange['max']);
}),
```

### Configuration Options
Sensible defaults can be set within the configuration file.  However, the following variables can be set on a per-filter basis
```php
DateRangeFilter::make('Created Date')
->config([
    'ariaDateFormat' => 'F j, Y',
    'dateFormat' => 'Y-m-d',
    'defaultStartDate' => date('Y-m-d'),
    'defaultEndDate' => date('Y-m-d'),
    'minDate' => '2022-01-01',
    'maxDate' => date('Y-m-d')
])
->filter(function (Builder $builder, array $dateRange) {
    $builder->where('created_at', '>=', $dateRange['min'])->where('created_at', '<=', $dateRange['max']);
}),
```

## Date Picker Filter
Flatpickr Filter with a configurable Minimum/Maximum value, provides one values to the filter() function

Include the class after your namespace declaration
```php
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DatePickerFilter;
```

In the filters() function in your data table component:



### Dependencies
This utilises the Flatpickr library.

There are several options for utilising this library!

#### NPM
Install flatpickr
```terminal
npm i flatpickr --save
```

Import flatpickr into your project's app.js
```js
import flatpickr from "flatpickr";
```


## SmartSelect Filter
A Select2 style Filter built in AlpineJS

Include the class after your namespace declaration
```php
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
```

In the filters() function in your data table component:
```php
SmartSelectFilter::make('Smart')
->options(
    Breed::query()
        ->orderBy('name')
        ->get()
        ->keyBy('id')
        ->map(fn ($breed) => $breed->name)
        ->toArray()
)->filter(function (Builder $builder, array $values) {
    return $builder->whereIn('breed_id', $values);
})
```

### Dependencies
This uses AlpineJS, there are no other dependencies.


# Publishing Assets
## Config
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-config
```

## CSS
### Including in WebPack / Vite etc
Add the following to your app.js file.
```js
import '../../vendor/lowerrocklabs/resources/css/numberRange.min.css';
```

### Publishing CSS
You may publish these to your public path using:
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-css
```

## Lang
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-lang
```

## Views
Please exercise restraint when publishing the views, as this package is in active development!
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-views
```
