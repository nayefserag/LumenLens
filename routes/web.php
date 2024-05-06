<?php
use Illuminate\Support\Facades\Route;
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
    $router->post('/', ['middleware' => 'validatePost', 'uses' => 'PostController@store']);
    $router->get('/comments', 'CommentController@all');
    $router->get('/{post_id}', ['middleware' => 'checkPostExists', 'uses' => 'PostController@show']);
    $router->patch('/{post_id}', ['middleware' => ['checkPostExists', 'validatePostUpdate'], 'uses' => 'PostController@update']);
    $router->delete('/{post_id}', ['middleware' => 'checkPostExists', 'uses' => 'PostController@destroy']);
    $router->get('/{post_id}/restore', ['middleware' => 'checkPostExists', 'uses' => 'PostController@restore']);

    $router->get('/{post_id}/comments', ['middleware' => 'checkPostExists', 'uses' => 'CommentController@getAllCommentsForPost']);
    $router->get('/{post_id}/comments/{comment_id}', ['middleware' => ['checkPostExists', 'checkCommentExists', 'checkCommentBelongsToPost'], 'uses' => 'CommentController@index']);
    $router->post('/{post_id}/comments', ['middleware' => ['checkPostExists', 'validateComment'], 'uses' => 'CommentController@store']);
    $router->put('/{post_id}/comments/{comment_id}', ['middleware' => ['checkPostExists', 'checkCommentExists', 'checkCommentBelongsToPost', 'validateUpdateComment'], 'uses' => 'CommentController@update']);
    $router->delete('/{post_id}/comments/{comment_id}', ['middleware' => ['checkPostExists', 'checkCommentExists', 'checkCommentBelongsToPost'], 'uses' => 'CommentController@destroy']);
});

// Routes for authentication
$router->group(['prefix' => 'auth', 'middleware' => 'auth'], function () use ($router) {
    $router->post('register', [ 'middleware' => 'validateUserCreation', 'uses' => 'AuthController@register']);
    $router->get('login', ['middleware' =>['validateLoginPayloade','checkUserExists'], 'uses' => 'AuthController@login']);
    $router->post('logout', 'AuthController@logout');
});
