<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Internity stores its HTML5 Blade templates in the project-root templates/
    | directory in accordance with Section 7 of the ICT 07 project guidelines.
    |
    */

    'paths' => [
        dirname(base_path()).DIRECTORY_SEPARATOR.'templates',
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

];
