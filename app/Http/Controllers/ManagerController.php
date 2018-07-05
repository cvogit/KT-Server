<?php

namespace App\Http\Controllers;

use App\Image;
use App\User;
use App\UserImage;
use App\Teacher;
use App\Student;
use App\StudentImage;
use App\Report;
use App\TeacherStudent;
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
	 * Get recources need for initial manager set up
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getManagerResource(Request $request)
	{
		$currentUser = $this->req->getUser();

		// Get active user list, not including self
		$users 		= User::where('active', 1)->where('id', '!=', $currentUser->id)->get(['id', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId', 'newReport']);
		// For each user, if they are a teacher, include teacher resources
		foreach ($users as $user) {
			$teacher = Teacher::Where('userId', $user->id)->first();
			if($teacher) {
				// Query teacher table
				$user->isTeacher = true;
				$user->teacher = Teacher::Where('userId', $user->id)->get(['userId', 'numStudents']);
				$user->teacher[0]->students = TeacherStudent::Where('teacherId', $user->id)->get(['studentId']);
				// Query user image list
				$user->images = UserImage::Where('userId', $user->id)->get(['imageId']);
				// Query user report list
				$user->reports = Report::Where('userId', $user->id)->get(['id']);
			} else {
				$user->isTeacher = false;
			}
		}

		// Get a list of new users
		$newUsers = User::where('new', 1)->get(['id', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId']);

		// Get students resources
		$students = Student::where('active', 1)->get(['id', 'firstName', 'lastName', 'DoB', 'description', 'avatarId']);
		// For each student, get their resources
		foreach ($students as $student) {
			// Query student image list
			$student->images = StudentImage::Where('studentId', $student->id)->get(['imageId']);
			//Query student report list
			$student->reports = Report::Where('studentId', $student->id)->get(['id']);
		}

		// Get reports list 
		$reports = Report::Get(['id', 'content']);

		return response()->json([
			'message' => "Succesfully fetch manager resources.",
			'result' 	=> array(
					'users' 		=> $users,
					'newUsers' 	=> $newUsers,
					'students'	=> $students,
					'reports'		=> $reports,
				)
			], 200);
	}
}