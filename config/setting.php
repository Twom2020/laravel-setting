<?php

return [
    /**
     * drivers to save setting
     *
     * can be 'database', ...
     */
    "driver" => env("SETTING_DRIVER", "database"),

    /**
     * drivers list
     */
    "drivers" => [
        "database" => [
            "provider" => \Twom\Setting\src\Drivers\DB::class,
        ],
        // json => [ provider => .... ]
    ],

    /**
     * statics settings
     */
    "settings" => [
        "app_name" => "laravel",
        "developer" => "ali ghale :)",
    ],


    /**
     * static defaults settings
     */
    "default" => [
        "another" => "does not have :("
    ],
];
