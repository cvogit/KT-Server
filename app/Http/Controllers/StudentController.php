<?php

namespace App\Http\Controllers;

use App\Student;
use App\Teacher;
use App\TeacherStudent;
use App\BasicForm;
use App\PregnancyForm;
use App\BirthForm;
use App\InfancyForm;
use App\ToddlerForm;
use App\FamilyForm;
use App\IllnessForm;
use App\EducationForm;
use App\PresentForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class StudentController extends Controller
{
	/**
	 * Activate a student
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function activate(Request $request, $studentId)
	{
		$student = Student::where('id', $studentId)->first();

		// Check if student is active
		if ( $student->active )
			return response()->json(['message' => "The student is already active."], 401);

		$student->active = 1;

		return response()->json(['message' => "Student have been assigned to teacher successfully."], 200);
	}

	/**
	 * Create a new student and forms
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		// Validate request
		$this->validate($request, [
			'name'					=>	'required|string|max:64'
			]);

		$basicForm 			= BasicForm::create();
		$familyForm 		= FamilyForm::create();
		$pregnancyForm 	= PregnancyForm::create();
		$birthForm 			= BirthForm::create();
		$infancyForm 		= InfancyForm::create();
		$toddlerForm 		= ToddlerForm::create();
		$illnessForm 		= IllnessForm::create();
		$educationForm 	= EducationForm::create();
		$presentForm 		= PresentForm::create();

		// Create student entry
		$student = Student::create([
			'name' 						=> $request->name,
			'basicFormId' 		=> $basicForm->id,
			'familyFormId' 		=> $familyForm->id,
			'pregnancyFormId' => $pregnancyForm->id,
			'birthFormId' 		=> $birthForm->id,
			'infancyFormId' 	=> $infancyForm->id,
			'toddlerFormId' 	=> $toddlerForm->id,
			'illnessFormId' 	=> $illnessForm->id,
			'educationFormId' => $educationForm->id,
			'presentFormId' 	=> $presentForm->id,
			]);

		return response()->json(['message' => "Student have been created successfully."], 200);
	}

	/**
	 * Deactivate a student
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function deactivate(Request $request, $studentId)
	{
		$student = Student::where('id', $studentId)->first();

		// Check if student is active
		if ( !$student->active )
			return response()->json(['message' => "The student is already inactive."], 401);

		$student->active = 0;

		return response()->json(['message' => "Student have been assigned to teacher successfully."], 200);
	}

	/**
	* Return a student
	*
	* @param \Illuminate\Http\Request
	* @param integer
	*
	* @return \Illuminate\Http\Response
	*/
	public function get(Request $request, $studentId)
	{
		// Validate request
		if ( !$this->req->isValidInt($studentId) )
			return response()->json(['message' => "Invalid id."], 404);

		$student = Student::find($studentId);

		if ( !$student )
			return response()->json(['message' => "Unable to get student."], 500);

		return response()->json([
			'message' => "Student details have been fetched successfully.",
			'result'  => $student
			], 200);
	}

	/**
	* Return a list of students
	*
	* @param \Illuminate\Http\Request
	* @param integer
	*
	* @return \Illuminate\Http\Response
	*/
	public function getActiveStudentsList(Request $request)
	{
		$students = Student::where('active', 1)->get();

		if ( !$students )
			return response()->json(['message' => "Unable to get students."], 500);
		
		return response()->json([
			'message' => "Student list have been fetched successfully.",
			'result'  => $students
			], 200);
	}

	/**
	* Return a list of students
	*
	* @param \Illuminate\Http\Request
	* @param integer
	*
	* @return \Illuminate\Http\Response
	*/
	public function getInactiveStudentsList(Request $request)
	{
		$students = Student::where('active', 0)->get();

		if ( !$students )
			return response()->json(['message' => "Unable to get students."], 500);
		
		return response()->json([
			'message' => "Student list have been fetched successfully.",
			'result'  => $students
			], 200);
	}

	/**
	 * Update a student
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response 
	 */
	public function update(Request $request, $studentId)
	{
		// Validate request
		$this->validate($request, [
			'description' 	=> 	'string|max:65535',
			'avatarPath' 		=> 	'string|max:64',
			'numSessions'		=>	'integer|min:0'
			]);

		$student = Student::find($studentId);

		if ( !$student )
			return response()->json(['message' => "Unable to find student."], 403);

		$data = $request->only('description', 'avatarPath', 'numSessions');

		$student->fill($data);

		return response()->json(['message' => "Student have been unassigned from teacher successfully."], 200);
	}

}