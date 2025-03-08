<?php

require_once __DIR__ . '/src/controllers/site/feed-controller.php';
require_once __DIR__ . '/src/controllers/site/auth-controller.php';
require_once __DIR__ . '/src/controllers/site/profile-controller.php';
require_once __DIR__ . '/src/controllers/site/search-controller.php';

class Routes{
    public static function getRouter(){
        return [
            'get' => [
                '/search' => 'site\SearchController@search'
            ],
            'post' => [
                
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
                ],
                'profile'=> [
                    'get' => [
                        '/{user_id}' => 'site\ProfileController@show',
                        '/{user_id}/edit' => 'site\ProfileController@edit',
                        '/{user_id}/follow' => 'site\ProfileController@follow',
                        '/{user_id}/unfollow' => 'site\ProfileController@unfollow'
                    ],
                    'post' => [
                        '/{user_id}/edit' => 'site\ProfileController@update'
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