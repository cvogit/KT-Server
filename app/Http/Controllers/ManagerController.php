<?php

namespace App\Http\Controllers;

use App\User;
use App\Teacher;
use App\Student;
use App\Helpers\RequestHelper;
use Auth;
use \Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Cvogit\LumenJWT\JWT;


class ManagerController extends Controller
{
	/**
	 * Get recources need for manager set up
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getManagerResource(Request $request)
	{
		// Get all active teachers
		$teachers = Teacher::get(['userId', 'numStudents', 'newReports']);
		foreach ($teachers as $teacher) {
			$teacher->user = User::where('id', $teacher->userId)->get(['id', 'firstName', 'lastName', 'avatarId', 'phoneNum']);
		}

		// Get users resources
		$users 		= User::where('active', 1)->get(['id', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId']);
		$newUsers = User::where('new', 1)->get(['id', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId']);

		// Get students resources
		$students = Student::where('active', 1)->get(['id', 'firstName', 'lastName', 'DoB', 'description', 'avatarId']);

		//

		return response()->json([
			'message' => "Succesfully fetch manager resources.",
			'result' 	=> array(
					'users' 		=> $users,
					'newUsers' 	=> $newUsers,
					'students'	=> $students,
					'teachers' 	=> $teachers,
				)
			], 200);
	}

}