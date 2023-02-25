# LaravelLivewireTablesAdvancedFilters
Advanced filters for Rappasoft Laravel Livewire Tables

Currently in Development - Please do not use these yet!

# Installation


# Publishing Assets
```terminal
php artisan vendor:publish

# Numeric Range Filter
Filter with a configurable Minimum/Maximum value, provides two values to the filter() function
![Number Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/NumberRangeFilter.png)

# Date Range Filter
Filter with a configurable Minimum/Maximum value, provides two values to the filter() function


# Configuration
The add-on has been designed to be highly customisable to suit various use cases.  Please see below for the options for configuring.  All configuration options can have defaults set in the config file, or can be set on the fly when creating a filter using the config() option.

# Numeric Range Filter
This depends on custom CSS, which can be included in your component by using the "numberRange.cssInclude" configuration option.
1. Used in-line, by setting numberRange.cssInclude to "inline" in the config file.
2. Included from a minified file, this requires publishing the CSS files, and setting numberRange.cssInclude to "include"
3. Included as part of your webpack/bundle, setting numberRange.cssInclude to "none".

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