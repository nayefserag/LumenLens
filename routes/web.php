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

// API version and basic app information
$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Routes for blog posts
$router->group(['prefix' => 'posts'], function () use ($router) {
    $router->get('/', 'PostController@index');
    $router->post('/', 'PostController@store');
    $router->get('/{id}', 'PostController@show');
    $router->put('/{id}', 'PostController@update');
    $router->delete('/{id}', 'PostController@destroy');

    // Nested routes for comments within posts
    $router->get('/{postId}/comments', 'CommentController@index');
    $router->post('/{postId}/comments', 'CommentController@store');
    $router->put('/comments/{id}', 'CommentController@update');
    $router->delete('/comments/{id}', 'CommentController@destroy');
});

// User management routes with authentication middleware
$router->group(['prefix' => 'users', 'middleware' => 'auth'], function () use ($router) {
    $router->post('/register', 'UserController@register');
    $router->post('/login', 'UserController@login');
    $router->get('/profile', 'UserController@profile');
    $router->put('/profile', 'UserController@updateProfile');
});
