<?php

namespace App\Http\Controllers;

use App\Image;
use App\User;
use App\UserImage;
use App\Teacher;
use App\Student;
use App\StudentImage;
use App\Report;
use App\BasicForm;
use App\PregnancyForm;
use App\BirthForm;
use App\InfancyForm;
use App\ToddlerForm;
use App\FamilyForm;
use App\EducationForm;
use App\PresentForm;
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
		$users 		= User::where('active', 1)->where('id', '!=', $currentUser->id)->get(['id', 'email', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId']);

		// Get a list of new users
		$newUsers = User::where('new', 1)->get(['id', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId']);

		// Get students resources
		$students = Student::where('active', 1)->get(['id', 'name', 'avatarId']);
		
		// For each student, get their resources
		foreach ($students as $student) {
			// Query student image list
			$student->images = StudentImage::Where('studentId', $student->id)->get(['imageId']);

			// Query student report list
			$student->reports = Report::Where('studentId', $student->id)->get();

			// For each report, get student name
			foreach ($student->reports as $report) {
				$report->student = Student::Where('id', $report->studentId)->get(['firstName', 'lastName'])->first();
			}

			// Query all of student forms
			$student->basicForm 		= BasicForm::Where('id', $student->basicFormId)->get();
			$student->familyForm 		= FamilyForm::Where('id', $student->familyFormId)->get();
			$student->pregnancyForm = PregnancyForm::Where('id', $student->pregnancyFormId)->get();
			$student->birthForm 		= BirthForm::Where('id', $student->birthFormId)->get();
			$student->infancyForm 	= InfancyForm::Where('id', $student->infancyFormId)->get();
			$student->toddlerForm 	= ToddlerForm::Where('id', $student->toddlerFormId)->get();
			$student->educationForm = EducationForm::Where('id', $student->educationFormId)->get();
			$student->presentForm 	= PresentForm::Where('id', $student->presentFormId)->get();
		}

		// Get all reports list 
		$reports = Report::Get();
		// For each report, get student name
		foreach ($reports as $report) {
			$report->student = Student::Where('id', $report->studentId)->get(['name'])->first();
		}

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