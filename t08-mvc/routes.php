<?php


class Routes{
    public static function getRouter(){
        return [
            'groups' => [
                '' => [
                    'get' => [
                        '/search' => 'site\SearchController@search',
                        '/' => 'site\FeedController@show'
                    ],
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
                    ],
                    'post' => [
                        '/{user_id}/store' => 'site\FeedController@store'
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