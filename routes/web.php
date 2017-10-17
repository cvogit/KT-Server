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

$router->post('register', 'RegisterController@register');

$router->get('login', 'AuthController@login');

// JWT protecteed routes
$router->group(['middleware' => 'jwt'], function () use ($router) {

	// Manager Routes
  $router->group(['middleware' => 'manager'], function () use ($router) {

  	//// Get routes ///

  	// Return all active users
  	$router->get('/users', 					'UserController@getUsers');

  	// Return user with id
  	$router->get('/users/{id}', 		'UserController@getUser');


  	//// Put routes ////

  	// Activate a user
	  $router->put('/activate/users/{id}', 		'UserController@activate');

	  // Deactivate a user
	  $router->put('/deactivate/users/{id}', 	'UserController@deactivate');

	  //// Post Routes ////

	  // Add user as a teacher
	  $router->post('/add/teachers/{id}', 		'TeacherController@add');

	  //// Delete Routes ////

	  // Remove user as a teacher
	  $router->delete('/remove/teachers/{id}', 	'TeacherController@remove');
	});

  // Teacher routes
  $router->group(['middleware' => 'teacher'], function () use ($router) {

  	//// Get routes ////
  	
  	// Get all students associate with teacher request
  	$router->get('/students', 			'StudentController@getStudents');

  	// Put routes
	  $router->get('/students/{id}', 	'StudentController@updateStudent');
	});

	
	// Return all active teachers
	$router->get('/teachers', 			'TeacherController@getTeachers');

	// Return teacher with id
	$router->get('/teachers/{id}', 	'TeacherController@getTeacher');

  // Update a user
  $router->put('/update/users/{id}', 	'UserController@updateUser');

  // upload an image associate with a user
  $router->post('/upload/users/image', 	'ImageController@userUpload');
});