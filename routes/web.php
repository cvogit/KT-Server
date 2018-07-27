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

	  $router->get('/students/active', 							'StudentController@getActiveStudentsList');
	  $router->get('/students/inactive', 						'StudentController@getInactiveStudentsList');
	  $router->put('/students/{studentId}/activate',	'StudentController@activate');
	  $router->put('/students/{studentId}/deactivate','StudentController@deactivate');

  	$router->get('/users', 												'UserController@getList');
  	$router->put('/users/{userId}/activate', 			'UserController@activate');
	  $router->put('/users/{userId}/deactivate',		'UserController@deactivate');

  	$router->post('/users/{userId}/roles/{role}', 	'UserController@setRole');
  	$router->delete('/users/{userId}/roles/{role}', 'UserController@deleteRole');

  	$router->get('/managers/resources', 					'ManagerController@getManagerResource');
	});

  // Access for any teacher
	$router->group(['middleware' => 'teacher'], function () use ($router) {
  	$router->get('/teachers/resources', 					'TeacherController@getTeacherResource');
	});

	// Access for any teacher or manager
	$router->group(['middleware' => 'manager_teacher'], function () use ($router) {
	  $router->post('/students', 												'StudentController@create');
	});

  // Access only for teacher own resources or manager
  $router->group(['middleware' => 'teacherResource'], function () use ($router) {

  	$router->get('/teachers/{teacherId}', 						'TeacherController@get');

  	$router->get('/teachers/{teacherId}/reports',			'ReportController@getTeacherReportsList');
	});

	// Access for any roles with access to student resources
  $router->group(['middleware' => 'studentResource'], function () use ($router) {

  	$router->get('/students/{studentId}/images', 							'ImageController@getStudentImagesList');
  	$router->get('/students/{studentId}/images/{imageId}',		'ImageController@getStudentImage');
  	$router->post('/students/{studentId}/images', 						'ImageController@createStudentImage');
  	$router->delete('/students/{studentId}/images/{imageId}', 'ImageController@removeStudentImage');

  	$router->get('/students/{studentId}', 					'StudentController@get');
  	$router->put('/students/{studentId}', 					'StudentController@update');

  	$router->get('/students/{studentId}/reports',		'ReportController@getStudentReportsList');

  	$router->put('students/{studentId}/forms/basic', 			'FormController@updateBasicForm');
  	$router->put('students/{studentId}/forms/pregnancy', 	'FormController@updatePregnancyForm');
  	$router->put('students/{studentId}/forms/birth', 			'FormController@updateBirthForm');
  	$router->put('students/{studentId}/forms/infancy', 		'FormController@updateInfancyForm');
  	$router->put('students/{studentId}/forms/toddler', 		'FormController@updateToddlerForm');
  	$router->put('students/{studentId}/forms/family', 		'FormController@updateFamilyForm');
  	$router->put('students/{studentId}/forms/illness', 		'FormController@updateIllnessForm');
  	$router->put('students/{studentId}/forms/education', 	'FormController@updateEducationForm');
  	$router->put('students/{studentId}/forms/present', 		'FormController@updatePresentForm');

		$router->post('/reports',											'ReportController@create');
		$router->put('/reports/{reportId}/contents/{contentNumber}',	'ReportController@update');

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

		$router->get('/teachers', 							'TeacherController@getList');

		$router->get('/users/{userId}/avatar',	'ImageController@getAvatar');
	});
});