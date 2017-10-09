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

$router->get('/', function () {
    return "Hello";
});

$router->put('register', 'RegisterController@register');

$router->get('login', 'AuthController@login');

// JWT protecteed routes
$router->group(['middleware' => 'jwt'], function () use ($router) {

	// Manager Routes
  $router->group(['middleware' => 'manager'], function () use ($router) {

  	$router->get('manager', function () {
		    return "Hello manager.";
		});
  	//// Get routes ///

  	// Return all active users
  	$router->get('/users', 'UserController@getUsers');

  	// Return user with id
  	$router->get('/users/{id}', 'UserController@getUser');

  	// Return all active teachers
  	$router->get('/teachers', 'TeacherController@getTeachers');

  	// Return teacher with id
  	$router->get('/teachers/{id}', 'TeacherController@getTeachers');

  	//// Put routes ////

  	// Activate a user
	  $router->get('/activate/{id}', 'UserController@activate');

	  // Deactivate a user
	  $router->get('/deactivate/{id}', 'UserController@deactivate');
	});

  // Teacher routes
  $router->group(['middleware' => 'teacher'], function () use ($router) {

  	//// Get routes ////
  	
  	// Get all students associate with teacher request
  	$router->get('/students', 'StudentController@getStudents');

  	// Put routes
	  $router->get('/students/{id}', 'StudentController@updateStudent');
	});
});