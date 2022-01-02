<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function() {
    return redirect()->route('index');
});

$router->group(['prefix' => 'aggressions'], function () use ($router) {
	$router->get('/', [
		'as' => 'index', 'uses' => 'AggressionController@index'
	]);
	$router->post('/', [
        'middleware' => 'throttle:2,1',
		'as' => 'store', 'uses' => 'AggressionController@store'
	]);
    $router->get('/exportCSV', [
        'as' => 'exportCSV', 'uses' => 'AggressionController@exportCSV'
    ]);
});

$router->group(['prefix' => 'admin'], function () use ($router) {
    $router->get('/index', [
        'as' => 'admin.index', 'uses' => 'AdminController@index'
    ]);
    $router->get('/login', [
        'middleware' => 'throttle:2,1',
        'as' => 'admin.showLogin', 'uses' => 'AdminController@showLogin'
    ]);
    $router->post('/login', [
        'middleware' => 'throttle:2,1',
        'as' => 'admin.login', 'uses' => 'AdminController@login'
    ]);
    $router->get('/logout', [
        'as' => 'admin.logout', 'uses' => 'AdminController@logout'
    ]);
    $router->put('/', [
        'as' => 'admin.moderate', 'uses' => 'AdminController@moderate'
    ]);
});
