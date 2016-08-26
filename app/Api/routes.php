<?php

$api = $app->make('Dingo\Api\Routing\Router');

$api->version(['v1'], function ($api) use ($app) {

        $api->get('/', function () use ($app, $api) {
            return [
                'message' => Auth::user(),
                'check' => Auth::check(),
                'status' => 200,
            ];
        });

        $api->post('/auth/login', 'App\Api\v1\AuthController@postLogin');
        $api->get('/auth/invalidate', 'App\Api\v1\AuthController@getInvalidate');

        $api->get('/user', ['middleware' => 'auth', function () {

            $user = Auth::user();

            return [
                'id' => $user->id,
                'name' => $user->username,
                'email' => $user->email,
                'random' => str_random(32),
            ];

        }]);

});