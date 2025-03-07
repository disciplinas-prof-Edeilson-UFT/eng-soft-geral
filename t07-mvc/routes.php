<?php

require_once __DIR__ . '/src/controllers/site/feed-controller.php';
require_once __DIR__ . '/src/controllers/site/auth-controller.php';

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
                        '/teste-group/{id}' => 'TesteController@showGroup'
                    ],
                    'post' => [
                        '/teste-group' => 'TesteController@storeGroup'
                    ]
                ],
                'auth' => [
                    'get' => [
                        '/login' => 'site\AuthController@showLogin',
                        '/signup' => 'site\AuthController@showSignup'
                    ],
                    'post' => [ 
                        '/login' => 'site\AuthController@login',
                        '/signup' => 'site\AuthController@signup'
                    ]
                ],
                'feed' => [
                    'get' => [
                        '/{user_id}' => 'site\FeedController@show'
                    ],
                    'post' => [
                        '/{user_id}' => 'site\FeedController@store'
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