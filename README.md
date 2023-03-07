![PHP Unit](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/actions/workflows/php-unit-pull.yml/badge.svg) 
![PHP Stan](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/actions/workflows/php-unit-pull.yml/badge.svg)

Dev Branch
![PHP Unit](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/actions/workflows/php-unit.yml/badge.svg?branch=develop)
![PHP Stan](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/actions/workflows/php-stan.yml/badge.svg?branch=develop)

![PHP Unit Coverage](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/badges/phpunit-coverage-8.2-10.*-develop.svg)

# LaravelLivewireTablesAdvancedFilters
Advanced filters for Rappasoft Laravel Livewire Tables v2.0 and above

* Numeric Range Filter
* Date Range Filter
* Date Picker Filter
* Smart Select (Select2 Style)
* Component Filter (Under Development)

Package is currently under active development & testing, please use caution when using in a production environment.
# Current Status
|        Filter     | Tailwind | Bootstrap 4 | Bootstrap 5 |
| :--- | :---: | :---: | :---: |
| Number Range      | Y    | Y  | Y    |
| Date Range        | Y    |Y  |  Y  | 
| Date/Time Picker  | Y    |Y |    Y   | 
| SmartSelect       | Y    |  UI Tweaks  |  UI Tweaks  |
| Component Filter  | Testing | N | N |

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
![Number Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/NumberRangeFilter-Light.png)

Include the class after your namespace declaration and standard Rappasoft filters
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
This depends on custom CSS, which can be included in your component by using one of three "numberRange.cssInclude" configuration options below.
1. Included within the blade, by setting numberRange.cssInclude to "inline" in the config file.
2. Included from a minified file, this requires publishing the CSS files, and setting numberRange.cssInclude to "include"
3. Included as part of your webpack/bundle, by setting numberRange.cssInclude to "none"

### Configuration Options
The colours in use are fully customisable, designed for class-based "dark/light" themes approach, as are the default min/max options, see guidance below.  Any of these can be over-ridden on a per-filter basis using the "config()" option on the filter.  You can set as many or as few configuration options as you like, the remainder will be set to the default from the configuration file.

```php
    'numberRange' => [
        'defaults' => [
            'min' => 0, // A Default Minimum Value
            'max' => 100,  // A Default Maximum Value
            'minRange' => 0,  // A Default Minimum Permitted Value
            'maxRange' => 100,  // A Default Maximum Permitted Value
            'suffix' => '%', // A Default Suffix

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
                'activeColor' => '#FFFFFF',
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

## Date Filters
There are two filters, one is a standard single-date picker (DatePickerFilter), and the other is a range filter (DateRangeFilter)

### Date Range Filter
Flatpickr Filter with a configurable Minimum/Maximum value, provides two values to the filter() function () in the form of an array.
![Date Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DateRange-Single.png)
![Date Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DateRange-RangeSelected.png)

Include the class after your namespace declaration
```php
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DateRangeFilter;
```

In the filters() function in your data table component:
```php
DateRangeFilter::make('Created Date')
->filter(function (Builder $builder, array $dateRange) {
    $builder->whereDate('created_at', '>=', $dateRange['minDate'])->whereDate('created_at', '<=', $dateRange['maxDate']);
}),
```

#### Configuration Options
Sensible defaults can be set within the configuration file.  However, the following variables can be set on a per-filter basis.  You can set as many or as few configuration options as you like, the remainder will be set to the default from the configuration file.

```php
DateRangeFilter::make('Created Date')
->config([
    'altFormat' => 'F j, Y', // The date format to be displayed once a date is selected
    'ariaDateFormat' => 'F j, Y', // The date format to be displayed for screen-readers
    'dateFormat' => 'Y-m-d', // The date format to be returned for use in the filter
    'earliestDate' => '2022-01-01', // The earliest date permitted, this is not required
    'latestDate' => date('Y-m-d') // The latest date permitted, this is not required
])
->filter(function (Builder $builder, array $dateRange) {
    $builder->whereDate('created_at', '>=', $dateRange['minDate'])
    ->whereDate('created_at', '<=', $dateRange['maxDate']);
}),
```

### Date Picker Filter
Flatpickr Filter with a configurable Minimum/Maximum value, provides one values to the filter() function
![Date Picker Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DatePicker-DateOnly.png)
![Date Picker Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DatePicker-Time.png)
![Date Picker Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DatePicker-TimeSelected.png)

Include the class after your namespace declaration
```php
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DatePickerFilter;
```

In the filters() function in your data table component:
```php
DatePickerFilter::make('Created Date')
->filter(function (Builder $builder, string $date) {
    $builder->where('created_at', '>=', $date);
}),
```

#### Configuration Options
Sensible defaults can be set within the configuration file.  However, the following variables can be set on a per-filter basis. You can set as many or as few configuration options as you like, the remainder will be set to the default from the configuration file.

```php
DatePickerFilter::make('Created Date')
->config([
    'altFormat' => 'F j, Y', // The date format to be displayed once a date is selected
    'ariaDateFormat' => 'F j, Y', // The date format to be displayed for screen-readers
    'dateFormat' => 'Y-m-d', // The date format to be returned for use in the filter
    'timeEnabled' => false, // Enable time selection
    'allowInput' => true, // Allow/disallow manual input into the text field
    'earliestDate' => '2022-01-01', // The earliest date permitted, this is not required
    'latestDate' => date('Y-m-d') // The latest date permitted, this is not required
])
->filter(function (Builder $builder, string $date) {
    $builder->where('created_at', '>=', $date);
}),

```
### Dependencies
This utilises the Flatpickr library.

There are several options for utilising the Flatpickr library!

#### NPM
Install flatpickr
```terminal
npm i flatpickr --save
```

Import flatpickr into your project's app.js
```js
import flatpickr from "flatpickr";
```

#### Including in the Views
Globally via the configuration file
```php
        // Set to true if you need to include the Flatpickr JS
        'publishFlatpickrJS' => false,
        // Set to true if you need to include the Flatpickr CSS
        'publishFlatpickrCSS' => false,
```
You can also set this at run-time via the config() option


## SmartSelect Filter
A Select2 style Filter built in AlpineJS.  This takes a list of potential options, and allows the end-user to filter them on-the-fly, and select appropriate values.  

Include the class after your namespace declaration
```php
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
```

To use this, you should pass the filter an array of options with values for the following keys, using the map function to return a relevant value for name.
* id
* name

This should take the format of: (# => { id: val, name: val })

In the filters() function in your data table component:

```php
    SmartSelectFilter::make('Parent')
    ->options(
        Tag::select('id', 'name', 'created_at')
            ->orderBy('name')
            ->get()
            ->map(function ($tag) {
                $tagValue['id'] = $tag->id;
                $tagValue['name'] = $tag->name;

                return $tagValue;
            })->keyBy('id')->toArray()
        )
    ->filter(function (Builder $builder, array $values) {
        $builder->whereHas('tags', fn ($query) => $query->whereIn('tags.id', $values));
    }),
```
To use the standard approach for the PopOver for Currently Selected Items, you should add a public array to your Data Component for storing the compiled selected items.  This offers the best end-user experience.

If you would prefer to utilise an on-the-fly Alpine lookup for the name, then you should set *popoverMethod* in the configuration to lookup

### Configuration options
The below can either be set in the configuration file, or specified per-filter by passing an array into the config() method of the filter. You can set as many or as few configuration options as you like, the remainder will be set to the default from the configuration file.


```php
'smartSelect' => [
    'popoverMethod' => 'standard',  // Should be set to either standard or lookup
    'iconStyling' => [
        'add' => [
            'classes' => '',        // Base classes for the "add" icon
            'defaults' => true,     // Determines whether to merge (true) or replace (false) the default class (inline-block)
            'svgEnabled' => false,  // Enable or Disable the use of the default SVG icon
            'svgFill' => '#000000', // Fill for the SVG Icon
            'svgSize' => '1.5em',   // Size for the SVG Icon
        ],
        'delete' => [
            'classes' => '',        // Base classes for the "delete" icon
            'defaults' => true,     // Determines whether to merge (true) or replace (false) the default class (inline-block)
            'svgEnabled' => true,   // Enable or Disable the use of the default SVG icon
            'svgFill' => '#000000', // Fill for the SVG Icon
            'svgSize' => '1.5em',   // Size for the SVG Icon
        ],
    ],
    'listStyling' => [
        'classes' => '',            // Classes for the list items
        'defaults' => true,         // Determines whether to merge (true) or replace (false) the default classes
    ],
    'closeAfterAdd' => true,        // Close the smartSelect after adding an item
    'closeAfterRemove' => true,     // Close the smartSelect after removing an item
],
```

### Dependencies
This uses AlpineJS, there are no other dependencies.

# Publishing Assets
To publish assets to make modifications, please see below

## Config
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-config
```

## CSS
### Including in WebPack / Vite etc
Add the following to your app.js file.
```js
import '../../vendor/lowerrocklabs/laravel-livewire-tables-advanced-filters/resources/css/numberRange.min.css';
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

## Other Notes
This package makes several on-the-fly adjustments to the default toolbar blade, including:
* Customisable width of the filter menu
* Filter menu will lock open until you click to close the menu
