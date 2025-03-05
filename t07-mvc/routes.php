<?php

require_once __DIR__ . '/src/controllers/site/home-controller.php';
require_once __DIR__ . '/src/controllers/site/teste-controller.php';

class Routes{
    public static function getRouter(){
        return [
            'get' => [
                '/' => 'site\HomeController@show',
                '/teste/{id}/{user}' => 'site\TesteController@show'
            ],
            'post' => [
                '/teste' => 'site\TesteController@store'
            ],
            'groups' => [
                '/admin' => [
                    'get' => [
                        '/teste-group/{id}' => 'site\TesteController@showGroup'
                    ],
                    'post' => [
                        '/teste-group' => 'site\TesteController@storeGroup'
                    ]
                ]
            ]
        ];
    }
    /*
        $routes = Routes::getRouter();
        $routes['groups']['/admin']['get']['/teste-group/{id}'] = 'site\TesteController@showGroup';
    */
}