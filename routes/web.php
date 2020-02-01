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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group(['middleware' => ['auth']], function ($router){
$router->get('/distributors', 'DistributorsController@index');

$router->post('/distributors', 'DistributorsController@store');

$router->get('/distributor/{id}', 'DistributorsController@show');

$router->put('/distributor/{id}', 'DistributorsController@update');

$router->delete('/distributor/{id}', 'DistributorsController@destroy');

$router->get('/books', 'BooksController@index');

$router->post('/books', 'BooksController@store');

$router->get('/book/{id}', 'BooksController@show');

$router->put('/book/{id}', 'BooksController@update');

$router->delete('/book/{id}', 'BooksController@destroy');

$router->get('/book/image/{imageName}', 'BooksController@image');

$router->get('/baskets', 'BasketsController@index');

$router->post('/baskets', 'BasketsController@store');

$router->get('/basket/{id}', 'BasketsController@show');

$router->put('/basket/{id}', 'BasketsController@update');

$router->delete('/basket/{id}', 'BasketsController@destroy');

$router->get('/purchases', 'PurchasesController@index');

$router->post('/purchases', 'PurchasesController@store');

$router->get('/purchase/{id}', 'PurchasesController@show');

$router->put('/purchase/{id}', 'PurchasesController@update');

$router->delete('/purchase/{id}', 'PurchasesController@destroy');
});

$router->get('/public/books', 'PublicController\BooksController@index');

$router->get('/public/book/{id}', 'PublicController\BooksController@show');

$router->get('/public/purchases', 'PublicController\PurchasesController@index');

$router->get('/public/purchase/{id}', 'PublicController\PurchasesController@show');

$router->get('/public/users', 'PublicController\UsersController@index');

$router->get('/public/user/{id}', 'PublicController\UsersController@show');

$router->get('/public/baskets', 'PublicController\BasketController@index');

$router->get('/public/basket/{id}', 'PublicController\BasketController@show');

$router->group(['prefix' => 'auth'], function() use ($router){
	$router->post('/register', 'AuthUsersController@register');
	$router->post('/login', 'AuthUsersController@login');
});