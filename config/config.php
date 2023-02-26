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
                //'activeColor' => '#000000',
                //  'fillColor' => '#000000',
                //  'primaryColor' => '#000000',
                //  'progressBackground' => '#F0F0F0',
                //  'thumbColor' => '#000000',
                //  'ticksColor' => 'silver',
                //  'valueBg' => 'transparent',
                //  'valueBgHover' => '#000000',
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
