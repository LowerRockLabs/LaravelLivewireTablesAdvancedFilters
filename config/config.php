<?php

// config for LowerRockLabs/LaravelLivewireTablesAdvancedFilters
return [
    'smartSelect' => [
        'defaults' => [
        ],
    ],
    'dateRange' => [
        'defaults' => [
            'allowInput' => true,
            'altFormat' => 'F j, Y',
            'ariaDateFormat' => 'F j, Y',
            'dateFormat' => 'Y-m-d',
        ],
        // Set to true if you need to include the Flatpickr JS
        'publishFlatpickrJS' => false,
        // Set to true if you need to include the Flatpickr CSS
        'publishFlatpickrCSS' => false,
    ],
    'datePicker' => [
        'defaults' => [

            'allowInput' => true,
            'altFormat' => 'F j, Y',
            'ariaDateFormat' => 'F j, Y',
            'dateFormat' => 'Y-m-d',
        ],
        // Set to true if you need to include the Flatpickr JS
        'publishFlatpickrJS' => false,
        // Set to true if you need to include the Flatpickr CSS
        'publishFlatpickrCSS' => false,
    ],
    'numberRange' => [
        'defaults' => [
            'min' => 0,
            'max' => 100,
        ],
        'styling' => [
            'light' => [
                'activeColor' => '#FFFFF',
                'fillColor' => '#0366d6',
                'primaryColor' => '#0366d6',
                'progressBackground' => '#eee',
                'thumbColor' => '#FFFFFF',
                'ticksColor' => 'silver',
                'valueBg' => 'transparent',
                'valueBgHover' => '#0366d6',
            ],
            'dark' => [
                'activeColor' => '#000000',
                'fillColor' => '#000000',
                'primaryColor' => '#000000',
                'progressBackground' => '#F0F0F0',
                'thumbColor' => '#000000',
                'ticksColor' => 'silver',
                'valueBg' => 'transparent',
                'valueBgHover' => '#000000',
            ],
        ],
        /*
        How to Include the CSS file.  Options are:
        inline - Pushes the <style></style> tags to the
        include - Requires you to publish the CSS file, and will then include it
        none - Do not include the CSS, note that you will need to include the CSS in your webpack.
        */
        'cssInclude' => 'inline',
    ],
];
