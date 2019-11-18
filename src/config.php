<?php

return [

    /*
    |--------------------------------------------------------------------------
    | output
    |--------------------------------------------------------------------------
    |
    | Filenames for the minified js and css files relative to public_path()
    |
    */

    'output' => [
        'css' => 'css/build/app.css',
        'js' => 'js/build/app.js',
    ],

    /*
    |--------------------------------------------------------------------------
    | skip_environment
    |--------------------------------------------------------------------------
    |
    | Don't minify in these environments
    | You shouldn't run live minification in production environments but run it
    | localy and push the minified output files to the production server
    |
    */

    'skip_environment' => [
        'production',
    ],

    /*
    |--------------------------------------------------------------------------
    | scssImportPaths
    |--------------------------------------------------------------------------
    |
    | Paths to search for CSS / SCSS @import
    |
    */

    'scssImportPaths' => [
        '../resources/sass/',
        '../resources/scss/',
        '../resources/css/',
        '../public/css/',
    ],

    /*
    |--------------------------------------------------------------------------
    | jsImportPaths
    |--------------------------------------------------------------------------
    |
    | Paths to search for javascript files
    |
    */

    'jsImportPaths' => [
        '../resources/js/',
        '../public/js/',
    ],

    /*
    |--------------------------------------------------------------------------
    | scssFormatter
    |--------------------------------------------------------------------------
    |
    | Default ScssPhp Formatter to use
    | Options: Expanded Nested Compact Compressed Crunched
    |
    */

    'scssFormatter' => '\ScssPhp\ScssPhp\Formatter\Crunched',

    /*
    |--------------------------------------------------------------------------
    | jsFlaggedComments
    |--------------------------------------------------------------------------
    |
    | Disable YUI style comment preservation.
    |
    */

    'jsFlaggedComments' => false,

];
