<?php

namespace App\Http\Controllers;

use App\Image;
use App\User;
use App\UserImage;
use App\Teacher;
use App\Student;
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
	 * Get recources need for manager set up
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getManagerResource(Request $request)
	{
		$currentUser = $this->req->getUser();

		// Get active user list, not including self
		$users 		= User::where('active', 1)->where('id', '!=', $currentUser->id)->get(['id', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId']);

		// For each user, if they are a teacher, include teacher resources
		foreach ($users as $user) {
			$teacher = Teacher::Where('userId', $user->id)->first();
			if($teacher) {
				// Query teacher table
				$user->isTeacher = true;
				$user->teacher = Teacher::Where('userId', $user->id)->get(['userId', 'numStudents', 'newReports']);
				$user->teacher[0]->students = TeacherStudent::Where('teacherId', $user->id)->get(['studentId']);
				// Query user image list
				$user->imageId = UserImage::Where('userId', $user->id)->get(['id']);
				// Query user report list
				$user->reports = Report::Where('userId', $user->id)->get(['studentId', 'content', 'new', 'update']);
			} else {
				$user->isTeacher = false;
			}
		}

		// Geta  list of new users
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
				)
			], 200);
	}

}