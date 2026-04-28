<?php

$presentation = [
    /*
    |--------------------------------------------------------------------------
    | Presentation Settings
    |--------------------------------------------------------------------------
    |
    | Centralized configuration for the Regatta Presentation / Slideshow.
    |
    */

    'times' => [
        'base' => 8,            // Base time in seconds for most slides
        'welcome' => 8,         // Fixed time for welcome slide
        'team_profile' => 10,   // Fixed time for team profiles (no rows)
        'video_default' => 120, // Default time for video if not specified
        'chars_per_sec' => 40,  // For information slide: 1s extra per X chars
    ],

    'limits' => [
        'teams_per_page' => 15,
        'table_rows_per_page' => 12,
    ],

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | show_team_profiles: 1 = always show team profiles, 0 = only if no results exist
    | show_background_image: 1 = show presentation background image, 0 = disable it
    |
    */
    'options' => [
        'show_team_profiles' => 0,
        'show_background_image' => 1,
    ],
];

$localOverrideFile = __DIR__ . '/presentation_options.php';
if (is_file($localOverrideFile)) {
    $localOverrides = include $localOverrideFile;
    if (is_array($localOverrides)) {
        $presentation = array_replace_recursive($presentation, $localOverrides);
    }
}

return $presentation;

