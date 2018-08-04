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
use App\IllnessForm;
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

		// Get active students resources
		$students = Student::where('active', 1)->get();
		
		// For each student, get their resources
		foreach ($students as $student) {
			// Query student image list
			$student->images = StudentImage::Where('studentId', $student->id)->get(['imageId']);

			// Query student report list
			$student->reports = Report::Where('studentId', $student->id)->get();

			// For each report, get student name
			foreach ($student->reports as $report) {
				$report->student = Student::Where('id', $report->studentId)->get(['name']);
			}

			// Query all of student forms
			$forms = array(
				'basicForm'	=> BasicForm::Where('id', $student->basicFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9']),
				'familyForm'=> FamilyForm::Where('id', $student->familyFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'question_10', 'question_11', 'question_12']),
				'pregnancyForm'=> PregnancyForm::Where('id', $student->pregnancyFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'question_10', 'question_11', 'question_12', 'question_13', 'question_14', 'question_15']),
				'birthForm'		=> 	BirthForm::Where('id', $student->birthFormId)->get(['question_1', 'question_2', 'question_3', 'question_4']),
				'infancyForm'	=> 	InfancyForm::Where('id', $student->infancyFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7']),
				'toddlerForm'	=> 	ToddlerForm::Where('id', $student->toddlerFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'question_10', 'question_11', 'question_12', 'question_13', 'question_14', 'question_15', 'question_16', 'question_17', 'question_18', 'question_19', 'question_20', 'question_21', 'question_22']),
				'illnessForm'	=> 	IllnessForm::Where('id', $student->illnessFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'question_10', 'question_11', 'question_12', 'question_13', 'question_14', 'question_15', 'question_16', 'question_17', 'question_18', 'question_19', 'question_20', 'question_21', 'question_22', 'question_23', 'question_24', 'question_25', 'question_26', 'question_27', 'question_28', 'question_29', 'question_30', 'question_31', 'question_32']),
				'educationForm'=> EducationForm::Where('id', $student->educationFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6']),
				'presentForm'	=> 	PresentForm::Where('id', $student->presentFormId)->get(['question_1', 'question_2', 'question_3', 'question_4']),
				);
			$student->forms = $forms;
		}

		// Get inactive students resources
		$inactiveStudents = Student::where('active', 0)->get();
		
		// For each student, get their resources
		foreach ($inactiveStudents as $student) {
			// Query student image list
			$student->images = StudentImage::Where('studentId', $student->id)->get(['imageId']);

			// Query student report list
			$student->reports = Report::Where('studentId', $student->id)->get();

			// For each report, get student name
			foreach ($student->reports as $report) {
				$report->student = Student::Where('id', $report->studentId)->get(['name']);
			}

			// Query all of student forms
			$forms = array(
				'basicForm'	=> BasicForm::Where('id', $student->basicFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9']),
				'familyForm'=> FamilyForm::Where('id', $student->familyFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'question_10', 'question_11', 'question_12']),
				'pregnancyForm'=> PregnancyForm::Where('id', $student->pregnancyFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'question_10', 'question_11', 'question_12', 'question_13', 'question_14', 'question_15']),
				'birthForm'		=> 	BirthForm::Where('id', $student->birthFormId)->get(['question_1', 'question_2', 'question_3', 'question_4']),
				'infancyForm'	=> 	InfancyForm::Where('id', $student->infancyFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7']),
				'toddlerForm'	=> 	ToddlerForm::Where('id', $student->toddlerFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'question_10', 'question_11', 'question_12', 'question_13', 'question_14', 'question_15', 'question_16', 'question_17', 'question_18', 'question_19', 'question_20', 'question_21', 'question_22']),
				'illnessForm'	=> 	IllnessForm::Where('id', $student->illnessFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'question_10', 'question_11', 'question_12', 'question_13', 'question_14', 'question_15', 'question_16', 'question_17', 'question_18', 'question_19', 'question_20', 'question_21', 'question_22', 'question_23', 'question_24', 'question_25', 'question_26', 'question_27', 'question_28', 'question_29', 'question_30', 'question_31', 'question_32']),
				'educationForm'=> EducationForm::Where('id', $student->educationFormId)->get(['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9']),
				'presentForm'	=> 	PresentForm::Where('id', $student->presentFormId)->get(['question_1', 'question_2', 'question_3', 'question_4']),
				);
			$student->forms = $forms;
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
					'inactiveStudents' => $inactiveStudents,
					'reports'		=> $reports,
				)
			], 200);
	}
}