<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */
    'component' => [
        'paths' => [
            'class' => getcwd() . '\app\View\Components',
            'render' => getcwd() . '\resources\views\component',
            'assets' => [
                'css' => getcwd() . '\public\css\components',
                'js' => getcwd() . '\public\css\components',
            ]
        ],
        'replace_component_exist' => true,
        'models' => null,
        'components' => [
            [
                'config' => [
                    'replace_component_exist' => true,
                    'models' => null,
                ],
                'name' => 'Modals/Roy',
                'args' => [
                    'string:array:title' => 'My Modal',
                    'string:description' => 'Lorem ipsum dolor sit amet',
                ]
            ],
            [
                'name' => 'Menu/Horiz',
                'args' => [
                    'array:datas' => '#!require'
                ]
            ]
        ]
    ],
    'layout' => []
];
