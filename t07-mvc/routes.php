<?php

require_once __DIR__ . '/src/controllers/home-controller.php';
require_once __DIR__ . '/src/controllers/teste-controller.php';

class Routes{
    public static function getRouter(){
        return [
            'get' => [
                '/' => 'HomeController@show',
                '/teste' => 'TesteController@show'
            ],
            'post' => [
                '/teste' => 'TesteController@store'
            ],
            'groups' => [
                '/admin' => [
                    'get' => [
                        '/teste-group' => 'TesteController@show'
                    ],
                    'post' => [
                        '/teste-group' => 'TesteController@store'
                    ]
                ]
            ]
        ];
    }
}