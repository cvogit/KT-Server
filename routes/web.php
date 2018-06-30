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

$router->get('login', 		'AuthController@login');


// JWT protecteed routes
$router->group(['middleware' => 'jwt'], function () use ($router) {

	// Access for managers
  $router->group(['middleware' => 'manager'], function () use ($router) {

		$router->post('/announcements', 							'AnnouncementController@create');
		$router->put('/announcements', 								'AnnouncementController@update');

 	  $router->get('/payments', 										'PaymentController@getList');
	  $router->get('/payments/{paymentId}', 				'PaymentController@get');
	  $router->post('/payments', 										'PaymentController@create');

	  $router->get('/payrolls', 										'PayrollController@getList');
	  $router->post('/payrolls', 										'PayrollController@create');
	  $router->delete('/payrolls/{payrollId}', 			'PayrollController@remove');

	  $router->put('reports/{reportId}/approve',		'ReportController@approve');
	  $router->put('reports/{reportId}/unapprove',	'ReportController@unapprove');


	  $router->get('/students/active', 							'StudentController@getActiveStudentsList');
	  $router->get('/students/inactive', 						'StudentController@getInactiveStudentsList');
	  $router->put('/students/activate',						'StudentController@activate');
	  $router->put('/students/deactivate', 					'StudentController@deactivate');
	  $router->post('/students/assign', 						'StudentController@assign');
	  $router->delete('/students/unassign', 				'StudentController@unassign');

	  $router->post('/teachers', 												'TeacherController@create');
	  $router->put('/teachers/{teacherId}/activate', 		'TeacherController@activate');
	  $router->put('/teachers/{teacherId}/deactivate', 	'TeacherController@deactivate');

  	$router->get('/users', 												'UserController@getList');
  	$router->put('/users/{userId}/activate', 			'UserController@activate');
	  $router->put('/users/{userId}/deactivate',		'UserController@deactivate');

  	$router->get('/managers/resources', 					'ManagerController@getManagerResource');
	});

  // Access for any teacher
	$router->group(['middleware' => 'teacher'], function () use ($router) {

	});

	// Access for any teacher or manager
	$router->group(['middleware' => 'manager_teacher'], function () use ($router) {

	  $router->post('/students', 												'StudentController@create');

	});

  // Access only for teacher own resources or manager
  $router->group(['middleware' => 'teacherResource'], function () use ($router) {

  	$router->get('/teachers/{teacherId}', 						'TeacherController@get');

  	$router->get('/teachers/{teacherId}/students', 		'StudentController@getTeacherStudentsList');

  	$router->get('/teachers/{teacherId}/reports',			'ReportController@getTeacherReportsList');
  	$router->post('/teachers/{teacherId}/reports',		'ReportController@create');
	});

	// Access for teachers with assigned student or managers
  $router->group(['middleware' => 'studentResource'], function () use ($router) {

  	$router->get('/students/{studentId}/images', 							'ImageController@getStudentImagesList');
  	$router->get('/students/{studentId}/images/{imageId}',		'ImageController@getStudentImage');
  	$router->post('/students/{studentId}/images', 						'ImageController@createStudentImage');
  	$router->delete('/students/{studentId}/images/{imageId}', 'ImageController@removeStudentImage');

  	$router->get('/students/{studentId}', 					'StudentController@get');
  	$router->put('/students/{studentId}', 					'StudentController@update');

  	$router->get('/students/{studentId}/reports',		'ReportController@getStudentReportsList');
	});

  // Access for user of own resource or manager
  $router->group(['middleware' => 'userResource'], function () use ($router) {

  	$router->get('/users/{userId}', 											'UserController@get');

  	$router->get('/users/{userId}/roles',									'UserController@getRoles');

		$router->get('/users/{userId}/images', 								'ImageController@getUserImagesList');
		$router->get('/users/{userId}/images/{userImgId}', 		'ImageController@getUserImage');

		$router->get('/users/{userId}/payments', 							'PaymentController@getUserPaymentsList');
		$router->get('/users/{userId}/payments/{paymentId}',	'PaymentController@getUserPayment');

	});

  // Access for user own resources
	$router->group(['middleware' => 'userPrivate'], function () use ($router) {

		$router->post('/users/{userId}/images', 							'ImageController@createUserImage');
		$router->delete('/users/{userId}/images/{imageId}',		'ImageController@removeUserImage');

		$router->put('/users/{userId}', 											'UserController@update');
		$router->put('/users/{userId}/avatar/{imgId}', 				'UserController@setAvatar');
	});

	// Routes for any active users
	$router->group(['middleware' => 'userActive'], function () use ($router) {

		$router->get('/announcements', 									'AnnouncementController@getList');
		$router->get('/announcements/{announcementId}',	'AnnouncementController@get');

		$router->get('/messages',								'MessageController@get');
		$router->post('/messages',							'MessageController@create');

		$router->get('/teachers', 				'TeacherController@getList');
	});
});