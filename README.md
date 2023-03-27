![PHP Unit](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/actions/workflows/php-unit-pull.yml/badge.svg) ![PHP Stan L8](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/actions/workflows/php-stan-pull.yml/badge.svg)  ![Test Coverage](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/image-data/coverage.svg)

![PHP Unit](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/actions/workflows/php-unit.yml/badge.svg?branch=develop) ![Test Coverage](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/image-data/coverage.svg)


# LaravelLivewireTablesAdvancedFilters
Advanced filters for Rappasoft Laravel Livewire Tables v2.0 and above

* Numeric Range Filter
* Date Range Filter
* Date Picker Filter
* Smart Select (Select2 Style)
* Component Filter (Under Development)

Demo Available Here:
[https://tabledemo.lowerrocklabs.com/](https://tabledemo.lowerrocklabs.com/)

Package is currently under active development & testing, please use caution when using in a production environment.

# Configuration
Please see the [Wiki](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/wiki) for detailed configuration options and examples.


# Current Status

|        Filter     | Tailwind 3 | Tailwind 2 | Bootstrap 4 | Bootstrap 5 |
| :--- | :---: | :---: | :---: | :---: |
| Number Range      | &check;    | &check;    | &check;  | &check; |
| Date Range        | &check;    | &check;    | &check;  |  &check;  | 
| Date/Time Picker  | &check;    | &check;    | &check; |    &check;   | 
| SmartSelect       | &check;    | &check;    | &check; <br />(Styling Improvements)  |  &check; <br />(Styling Improvements)  |
| Component Filter  | Testing   | Testing | &cross;  | &cross;  |

# Laravel Support
| Version | Supported |
| :---: | :---: | 
| 8 | &check;  |
| 9 | &check;  |
| 10 | &check;  |


# Installation
This package is available to be installed via Composer
```terminal
composer require lowerrocklabs/laravel-livewire-tables-advanced-filters
```

# The Filters
Filter classes should be in your table's head in the same way as existing filters.

## Numeric Range Filter
Filter with a configurable Minimum/Maximum value, provides two values to the filter() function

| ![Number Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/NumberRangeFilter.png)
| ![Number Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/NumberRangeFilter-Light.png) |


## Date Filters
There are two filters, one is a standard single-date picker (DatePickerFilter), and the other is a range filter (DateRangeFilter)

### Date Range Filter
Flatpickr Filter with a configurable Minimum/Maximum value, provides two values to the filter() function () in the form of an array.
![Date Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DateRange-Single.png)
![Date Range Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DateRange-RangeSelected.png)


### Date Picker Filter
Flatpickr Filter with a configurable Minimum/Maximum value, provides one values to the filter() function

| ![Date Picker Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DatePicker-DateOnly.png) | 
![Date Picker Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DatePicker-Time.png) | 
![Date Picker Filter](https://github.com/LowerRockLabs/LaravelLivewireTablesAdvancedFilters/blob/develop/docs/images/DatePicker-TimeSelected.png) |



## SmartSelect Filter
A Select2 style Filter built in AlpineJS.  This takes a list of potential options, and allows the end-user to filter them on-the-fly, and select appropriate values. 


# Other Notes
This package makes several on-the-fly adjustments to the default toolbar blade, including:
* Customisable width of the filter menu
    Set the following value in the configuration file.  You may pass any valid width class/selectors to this field.
    'customFilterMenuWidth' => 'md:w-80',
* Filter menu will lock open until you click to close the menu


# Publishing Assets

## CSS
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

