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

  	// Return all active users
  	$router->get('/users', 										'UserController@getUsers');

  	// Return user with id
  	$router->get('/users/{id}', 							'UserController@getUser');

  	// Activate a user
	  $router->put('/activate/users/{id}', 			'UserController@activate');

	  // Deactivate a user
	  $router->put('/deactivate/users/{id}', 		'UserController@deactivate');

	  // Return all payments
	  $router->get('/payments', 								'PaymentController@get');

	  // Return all payments belong to a user
	  $router->get('/payments/{id}', 						'PaymentController@getUser');

	  // Log a payment to a user
	  $router->post('/payments/{id}', 					'PaymentController@add');

	  // Return all payrolls
	  $router->get('/payrolls', 								'PayrollController@get');

	  // Add user to payroll
	  $router->post('/payrolls/{id}', 					'PayrollController@add');

	  // Remove user from payroll
	  $router->delete('/payrolls/{id}', 				'PayrollController@remove');

	  // Add user as a teacher
	  $router->post('/teachers/{id}', 					'TeacherController@add');

	  // Remove user as a teacher
	  $router->delete('/teachers/{id}', 				'TeacherController@remove');
	});

  // Teacher routes
  $router->group(['middleware' => 'teacher'], function () use ($router) {
  	
  	// Get all students associate with teacher request
  	$router->get('/students', 											'StudentController@getStudents');

  	// Put routes
	  $router->get('/students/{id}', 									'StudentController@updateStudent');

	  // Get an image associate with a student id
  	$router->get('/images/students/{id}/{picId}', 	'ImageController@studentUpload');

	  // upload an image associate with a student id
  	$router->post('/images/upload/students/{id}', 	'ImageController@studentUpload');
	});

	// Return all active teachers
	$router->get('/teachers', 										'TeacherController@getTeachers');

	// Return teacher with id
	$router->get('/teachers/{id}', 								'TeacherController@getTeacher');

	// Return all pictures ids belong to user making the request
	$router->get('/images/users', 								'ImageController@getUserImgId');

	// Return a picture belong to user
	$router->get('/images/users/{imgId}', 				'ImageController@getUserImg');

	// upload an image associate with a user
  $router->post('/images/user', 								'ImageController@addUserImg');

  // upload an image associate with a user
  $router->delete('/images/user/{imgId}', 			'ImageController@removeUserImg');

  // Update a user
  $router->put('/update/users/self', 						'UserController@update');
});