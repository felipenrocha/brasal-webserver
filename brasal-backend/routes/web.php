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


//Rota para debugar
$router->get('/', function () use ($router) {
    return $router->app->version();
});



// rotas de autenticacao


// register post route
$router->post('register', 'AuthController@register');

//login 
$router->post('login', 'AuthController@login');

//logout
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('logout', 'AuthController@logout');
});
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('user', 'AuthController@user');
});


// rota api para tipos de status de uma tarefa (relacionamento da tabela) feito no cadastro.

$router->get('api/status/get', 'StatusController@read');


//get status values


// rotas do crud


// crud autenticado pelo token (um usuario so deve poder atualizar suas tarefas)
$router->group(['middleware' => 'auth'], function () use ($router) {
    //create
    $router->post('api/tarefas/create', 'TarefaController@create');
    //read
    $router->get('api/tarefas/get', 'TarefaController@get_all');

    $router->get('api/tarefas/get/{id}', 'TarefaController@read');
    //update
    $router->put('api/tarefas/update/{id}', 'TarefaController@update');
    //delete
    $router->delete('api/tarefas/delete/{id}', 'TarefaController@delete');


});