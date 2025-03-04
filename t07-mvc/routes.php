<?php

require_once __DIR__ . '/src/controllers/home-controller.php';

class Routes{
    public static function getRoutes(){
        return [
            'get' => [
                '/' => 'HomeController@show',
                '/teste' => 'testeController@show'
            ],
            'post' => [
                '/teste' => 'testeController@store'
            ]
        ];
    }
}