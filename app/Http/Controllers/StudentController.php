<?php

namespace App\Http\Controllers;

use App\Student;
use App\Teacher;
use App\TeacherStudent;
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
	public function activate(Request $request)
	{
		// Validate request
		$this->validate($request, [
			'studentId' 					=> 'required|integer'
			]);

		$student = Student::where('id', $request->studentId)->first();

		// Check if student is active
		if ( $student->active )
			return response()->json(['message' => "The student is already active."], 401);

		$student->active = 1;

		return response()->json(['message' => "Student have been assigned to teacher successfully."], 200);
	}

	/**
	 * Create a new student and assign to teacher making the request
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		// Validate request
		$this->validate($request, [
			'firstName'			=>	'required|string|max:64',
			'lastName'			=>	'required|string|max:64',
			'DoB' 					=> 	'required|date',
			'description' 	=> 	'string'
			]);

		// Check if student is already created
		if( Student::where('firstName', $request->firstName)->where('lastName', $request->lastName)->where('DoB', $request->DoB)->first())
			return response()->json(['message' => "Student  is already registered."], 403);

		$user = $this->req->getUser();

		// Get user teacher info
		$teacher = Teacher::where('userId', $user->id)->first();

		// Create student entry
		$student = Student::create([
			'firstName' 		=> $request->firstName,
			'lastName' 			=> $request->lastName,
			'DoB' 					=> $request->DoB,
			]);

		$teacher->numStudents++;
		$teacher->save();

		// Set student to teacher
		TeacherStudent::create([
			'teacherId' => $teacher->id,
			'studentId' => $student->id
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
	public function deactivate(Request $request)
	{
		// Validate request
		$this->validate($request, [
			'studentId' 					=> 'required|integer'
			]);

		$student = Student::where('id', $request->studentId)->first();

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
	* Return a list of students belong to teaher
	*
	* @param \Illuminate\Http\Request
	* @param integer
	*
	* @return \Illuminate\Http\Response
	*/
	public function getTeacherStudentsList(Request $request, $teacherId)
	{
		$teacher = Teacher::find($teacherId);

		if ( !$teacher )
			return response()->json(['message' => "Unable to get teacher."], 500);

		$students = TeacherStudent::where('teacherId', $teacherId)->get();

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