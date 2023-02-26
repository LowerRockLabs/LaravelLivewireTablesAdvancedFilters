# LaravelLivewireTablesAdvancedFilters
Advanced filters for Rappasoft Laravel Livewire Tables v2.0 and above

Currently in Development - Please do not use these yet!

# Installation
This is available to be installed via Composer
```terminal
composer require lowerrocklabs/laravel-livewire-tables-advanced-filters
```

# Publishing Assets
## Config
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-config
```

## CSS
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-css
```

## Lang
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-lang
```

## Views
```terminal
php artisan vendor:publish livewiretablesadvancedfilters-views
```

# Numeric Range Filter
Filter with a configurable Minimum/Maximum value, provides two values to the filter() function
![Number Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/NumberRangeFilter.png)

# Date Range Filter
Flatpickr Filter with a configurable Minimum/Maximum value, provides two values to the filter() function

# Date Picker Filter
Flatpickr Filter with a configurable Minimum/Maximum value, provides one values to the filter() function

# Select2 Filter
A Select2 style Filter built in AlpineJS

# Configuration
The add-on has been designed to be highly customisable to suit various use cases.  Please see below for the options for configuring.  All configuration options can have defaults set in the config file, or can be set on the fly when creating a filter using the config() option.

# Numeric Range Filter
This depends on custom CSS, which can be included in your component by using the "numberRange.cssInclude" configuration option.
1. Used in-line, by setting numberRange.cssInclude to "inline" in the config file.
2. Included from a minified file, this requires publishing the CSS files, and setting numberRange.cssInclude to "include"
3. Included as part of your webpack/bundle, setting numberRange.cssInclude to "none".

# SmartSelect Filter
This uses AlpineJS, no other dependencies.

# Date Range Filter
This utilises the Flatpickr library.

There are several options for utilising this library!

## NPM
```terminal
npm i flatpickr --save
```

Import flatpickr into your project
```js
import flatpickr from "flatpickr";
```