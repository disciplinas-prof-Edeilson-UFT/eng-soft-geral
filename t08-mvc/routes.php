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
                        '/login' => 'site\AuthController@loginForm',
                        '/signup' => 'site\AuthController@signupForm',
                        '/logout' => 'site\AuthController@logout' 
                    ],
                    'post' => [ 
                        '/login' => 'site\AuthController@login',
                        '/signup' => 'site\AuthController@signup'
                    ]
                ],
                'feed' => [
                    'middleware' => ['auth'],
                    'get' => [
                    ],
                    'post' => [
                        '/{user_id}/store' => 'site\FeedController@store',
                        '/{post_id}/delete' => 'site\FeedController@delete'
                    ]
                ],
                'profile'=> [
                    'middleware' => ['auth'],
                    'get' => [
                        '/{user_id}' => 'site\ProfileController@show',
                        '/{user_id}/edit' => 'site\ProfileController@edit',

                    ],
                    'post' => [
                        '/{user_id}/edit' => 'site\ProfileController@update',
                        '/{user_id}/follow' => 'site\ProfileController@follow'
                    ]
                ]
            ]
        ];
    }
}