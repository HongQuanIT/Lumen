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
use App\Http\Controllers\AuthController;
// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });
$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});
//login
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    #------------------------------/api/register

    $router->post('register', 'AuthController@register');

    #------------------------------/api/login
    $router->post('login', 'AuthController@login');

    $router->group(['middleware' => ['jwt']],function () use ($router) {

       #---------------------Auth------------------------
       $router->post('refresh', 'AuthController@refresh');
       $router->post('logout', 'AuthController@logout');
       #-------------------End Auth----------------------

       $router->post('ok', function(){
           return "ok";
       });
       $router->get('getAllUser', 'UserController@getAllUser');

    });
});
