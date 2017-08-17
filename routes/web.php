<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'xjtu'], function () use ($app) {
    $app->group(['prefix' => 'user'], function () use ($app) {
        $app->get('info', ['middleware' => 'auth:api.xjtu.user.info', 'uses' => 'Xjtu\UserController@getUserInfo']);
        $app->get('photo', ['middleware' => 'auth:api.xjtu.user.photo', 'uses' => 'Xjtu\UserController@getUserPhoto']);
    });
});
