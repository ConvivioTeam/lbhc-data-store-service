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

/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(
    ['namespace' => 'Test', 'prefix' => 'test'],
    function () use ($router) {
        $router->group(
            ['prefix' => 'providers'],
            function () use ($router) {
                $router->get('/', ['uses' => 'ProviderController@index']);
                $router->get('{id}', ['uses' => 'ProviderController@get']);
                $router->post('/', ['uses' => 'ProviderController@create']);
                $router->put('{id}', ['uses' => 'ProviderController@update']);
            }
        );
    }
);
