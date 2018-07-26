<?php

namespace App\Http\Controllers;

use App\User;
use App\Report;
use App\Student;
use App\StudentImage;
use App\Manager;
use App\Teacher;
use App\TeacherStudent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TeacherController extends Controller
{
	/**
	 * Create a new teacher
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		// Validate request
		$this->validate($request, [
			'userId' 		=> 'required|integer'
			]);

		// Find the user to be activated in db
		$user = User::find($request->userId);

		// Return error if can't find user with $id in db
		if( !$user || !$user->active  )
			return response()->json(['message' => 'Invalid request, user does not exist or is not active.'], 404);

		// Check if user is already in teacher table
		if( Teacher::where('userId', $user->id)->first() )
			return response()->json(['message' => 'The user is already a teacher.'], 404);

		$teacher = Teacher::create([
      'userId' => $user->id,
  	]);

  	if (!$teacher)
  		return response()->json(['message' => "Server error."], 500);

		return response()->json([
			'message' => 'The teacher have been added successfully.'
			], 200);
	}

	/**
	 * Activate a teacher
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function activate(Request $request, $teacherId)
	{
		if ( !$this->req->isValidInt($teacherId) )
			return response()->json(['message' => 'Invalid request.'], 404);

		// Find teacher to be activate
		$teacher = Teacher::find($teacherId);

		// Return error if can't find user with $id in db
		if( !$teacher )
			return response()->json(['message' => 'Invalid request, the teacher does not exist.'], 404);

		if ( $teacher->active )
			return response()->json(['message' => 'Invalid request, the teacher is already active.'], 404);

		$teacher->active = 1;
		$teacher->save();

		return response()->json([
			'message' => 'The teacher have been activated successfully.'
			], 200);
	}

	/**
	 * Assigns student to a teacher
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function assignStudent(Request $request, $teacherId, $studentId)
	{

		$student = Student::where('id', $studentId)->first();

		// Check if student is active
		if (!$student->active)
			return response()->json(['message' => "Unable to find student."], 403);

		// Check if the teacher exist
		$teacher = Teacher::where('id', $teacherId)->first();

		if ( !$teacher )
			return response()->json(['message' => "Unable to find teacher."], 403);

		// Check if student is already assigned to teacher
		$assigned = TeacherStudent::where('teacherId', $teacherId)->where('studentId', $studentId)->first();

		if ( $assigned )
			return response()->json(['message' => "Student already is assigned to teacher."], 403);

		// Set student to teacher
		$assigned = TeacherStudent::create([
			'teacherId' => $teacherId,
			'studentId' => $studentId
			]);

		if ( !$assigned )
			return response()->json(['message' => "Unable to assign student to teacher."], 404);

		$student->assigned = 1;
		$student->save();

		$teacher->numStudents++;
		$teacher->save();

		return response()->json(['message' => "Student have been assigned to teacher successfully."], 200);
	}

	/**
	 * Deactivate a teacher
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function deactivate(Request $request, $teacherId)
	{
		if ( !$this->req->isValidInt($teacherId) )
			return response()->json(['message' => 'Invalid request.'], 404);

		// Find teacher to be deactivate
		$teacher = Teacher::find($teacherId);

		// Return error if can't find user with $id in db
		if( !$teacher )
			return response()->json(['message' => 'Unable to find teacher.'], 404);

		if ( !$teacher->active )
			return response()->json(['message' => 'Invalid request, the teacher is already not active.'], 404);

		$teacher->active = 0;
		$teacher->save();

		return response()->json([
			'message' => 'The teacher have been deactivated successfully.'
			], 200);
	}

	/**
	 * Return a teacher detail
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(Request $request, $teacherId)
	{	
		// Validate $id
		if ( !$this->req->isValidInt($teacherId) )
			return response()->json(['message' => 'The request parameters are invalid.'], 404);

		// Find teacher to be return
		$teacher = Teacher::find($teacherId);

		if ( !$teacher )
			return response()->json(['message' => 'Unable to find teacher.'], 404);

		return response()->json([
			'message' => "Succesfully fetch teacher.",
			'result' 	=> $teacher
			], 200);
	}

	/**
	 * Return all teachers
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getList(Request $request)
	{	
		// Validate request
		$this->validate($request, [
			'offset' 		=> 'integer',
			]);

		$offset = 0;
		$limit  = 20;

		if ( $request->has('offset') )
			$offset = $request->input('offset');

		$teachers = Teacher::skip($offset)->take($limit)->get();

		return response()->json([
			'message' => "Succesfully fetch all teachers.",
			'result' 	=> $teachers,
			'offset'	=> $offset+$limit
			], 200);
	}

	/**
	 * Return teacher initial resources
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getTeacherResource(Request $request)
	{

		$user = $this->req->getUser();

		$teacher = Teacher::where('userId', $user->id)->get(['id', 'userId', 'numStudents', 'newReports']);
		$teacher = $teacher[0];

		if(!$teacher) {
			return response()->json(['message' => 'Unable to find teacher.'], 404);
		}

		// Get list of managers resource
		$managersId = Manager::get(['userId']);
		$managers = [];
		foreach ($managersId as $manager) {
			array_push($managers, User::where('id', $manager->userId)->get(['id', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId', 'newReport'])[0]);
		}
		$teacher->managers = $managers;

		// Get all students' resources assigned to the teacher
		$studentsId = TeacherStudent::Where('teacherId', $teacher->id)->get(['studentId']);
		$idArray = [];
		foreach ($studentsId as $json) {
			array_push($idArray, $json->studentId);
		}

		$teacher->students = Student::where('active', 1)->whereIn('id', $idArray)->get(['id', 'firstName', 'lastName', 'DoB', 'description', 'avatarId']);

		// Query students resource
		foreach ($teacher->students as $student) {
			// Query student image list
			$student->images = StudentImage::Where('studentId', $student->id)->get(['imageId']);

			// Query student report list
			$student->reports = Report::Where('studentId', $student->id)->get(['id', 'userId', 'studentId', 'content', 'created_at']);

			// For each report, get student name
			foreach ($student->reports as $report) {
				$report->student = Student::Where('id', $report->studentId)->get(['firstName', 'lastName'])->first();
			}
		}

		// Get all teacher reports
		$teacher->reports = Report::Where('userId', $user->id)->get(['id', 'userId', 'studentId', 'content']);

		return response()->json([
			'message' => "Succesfully teacher resources.",
			'result' 	=> $teacher
			], 200);
	}

	/**
	 * Unassigns student from a teacher
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response 
	 */
	public function unAssignStudent(Request $request, $teacherId, $studentId)
	{
		// Check if the teacher exist
		$teacher = Teacher::find($teacherId);

		if ( !$teacher )
			return response()->json(['message' => "Unable to find teacher."], 403);

		// Check if student exist
		$student = Student::find($studentId);

		if ( !$student )
			return response()->json(['message' => "Unable to find student."], 403);

		// Check if student is assigned to teacher
		$assigned = TeacherStudent::where('teacherId', $teacherId)->where('studentId', $studentId)->first();

		if (!$assigned)
			return response()->json(['message' => "Student already not assigned to teacher."], 403);

		$assigned->delete();

		$stillAssigned = TeacherStudent::where('studentId', $studentId)->first();

		if (!$stillAssigned)
			$student->assigned = 0;

		$student->save();

		$teacher->numStudents--;
		$teacher->save();

		return response()->json(['message' => "Student have been unassigned from teacher successfully."], 200);
	}
}