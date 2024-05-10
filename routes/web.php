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

// Routes for blog posts
$router->group(['prefix' => 'posts', 'middleware' => 'jwtMiddleware'], function () use ($router) {
    $router->get('/', 'PostController@index');
    $router->post('/', ['middleware' => 'validate:post', 'uses' => 'PostController@store']);
    $router->get('/comments', 'CommentController@all');
    $router->get('/{post_id}',  'PostController@show');
    $router->patch('/{post_id}', ['middleware' =>  'validate:post', 'uses' => 'PostController@update']);
    $router->delete('/{post_id}', ['middleware' => 'checkPostExists', 'uses' => 'PostController@destroy']);
    $router->get('/{post_id}/restore', ['middleware' => 'checkPostExists', 'uses' => 'PostController@restore']);

    $router->get('/{post_id}/comments', ['middleware' => 'checkPostExists', 'uses' => 'CommentController@getAllCommentsForPost']);
    $router->get('/{post_id}/comments/{comment_id}', ['middleware' => ['checkPostExists', 'checkCommentExists', 'checkCommentBelongsToPost'], 'uses' => 'CommentController@index']);
    $router->post('/{post_id}/comments', ['middleware' => ['checkPostExists', 'validate:comment'], 'uses' => 'CommentController@store']);
    $router->put('/{post_id}/comments/{comment_id}', ['middleware' => ['checkPostExists', 'checkCommentExists', 'checkCommentBelongsToPost', 'validate:comment'], 'uses' => 'CommentController@update']);
    $router->delete('/{post_id}/comments/{comment_id}', ['middleware' => ['checkPostExists', 'checkCommentExists', 'checkCommentBelongsToPost'], 'uses' => 'CommentController@destroy']);
});

// Routes for authentication
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', ['middleware' => 'validate:user', 'uses' => 'AuthController@register']);
    $router->post('login', ['middleware' => ['validateLoginPayloade', 'checkUserExists'], 'uses' => 'AuthController@login']);

    $router->group(['middleware' => 'jwtMiddleware'], function () use ($router) {
        $router->post('logout', 'AuthController@logout');
    });
});

$router->group(['middleware' => 'jwtMiddleware'], function () use ($router) {
    $router->get('/protected', function () {
        return response()->json(['message' => 'This is a protected route']);
    });
});

$router->group(['prefix' => 'user', 'middleware' => 'jwtMiddleware'], function () use ($router) {
    $router->get('/me', 'UserController@me');
    $router->patch('/profile/update', ['middleware' => ['validate:user'], 'uses' => 'UserController@update']);
    $router->delete('/delete', 'UserController@deleteuser');
});
