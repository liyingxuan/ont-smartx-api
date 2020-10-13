<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Api Doc
 */
Route::get('/doc', '\App\Api\Common\ApiDoc@index');

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->post('register', 'AuthController@register');
        $api->post('login', 'AuthController@login');
        $api->post('token/refresh', 'AuthController@refresh');
        $api->post('logout', 'AuthController@logout');
        $api->post('github/login', 'AuthController@githubLogin');
    });

    /**
     * Token Auth
     */
    $api->group(['namespace' => 'App\Api\Controllers', 'middleware' => 'auth:api'], function ($api) {
        // User
        $api->group(['prefix' => 'user'], function ($api) {
            $api->post('info', 'UserController@getAuthenticatedUser');
            $api->post('github/add', 'UserController@AddGithubId');
            $api->post('ontid/add', 'UserController@AddOntId');
            $api->post('github/delete', 'UserController@DeleteGithubId');
            $api->post('ontid/delete', 'UserController@DeleteOntId');
        });

        // Project
        $api->group(['prefix' => 'project'], function ($api) {
            $api->get('/', 'ProjectController@index');
            $api->get('list', 'ProjectController@list');
            $api->post('create', 'ProjectController@create');
            $api->get('/{id}', 'ProjectController@show');
            $api->post('update', 'ProjectController@update');
            $api->post('del', 'ProjectController@destroy');
        });

        // Template
        $api->group(['prefix' => 'template'], function ($api) {
            $api->post('list', 'TemplateController@namelist');
            $api->post('code', 'TemplateController@code');
        });

        // Public Lib
        $api->group(['prefix' => 'public-libs'], function ($api) {
            $api->get('/', 'PublicLibController@index');
            $api->post('list', 'PublicLibController@list');
        });
    });
});
