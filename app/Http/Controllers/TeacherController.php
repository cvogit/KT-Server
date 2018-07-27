<?php

namespace App\Http\Controllers;

use App\User;
use App\Report;
use App\Student;
use App\StudentImage;
use App\Manager;
use App\Teacher;
use App\TeacherStudent;
use App\BasicForm;
use App\PregnancyForm;
use App\BirthForm;
use App\InfancyForm;
use App\ToddlerForm;
use App\FamilyForm;
use App\EducationForm;
use App\PresentForm;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TeacherController extends Controller
{
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

		$teacher = Teacher::where('userId', $user->id)->get(['id', 'userId']);
		$teacher = $teacher[0];

		if(!$teacher) {
			return response()->json(['message' => 'Unable to find teacher.'], 404);
		}

		// Get list of managers resource
		$managersId = Manager::get(['userId']);
		$managers = [];
		foreach ($managersId as $manager) {
			array_push($managers, User::where('id', $manager->userId)->get(['id', 'firstName','lastName', 'phoneNum', 'lastLogin', 'avatarId'])[0]);
		}
		$managers = $managers;

		// Get all students
		$students = Student::where('active', 1)->get();

		// Query students resource
		foreach ($students as $student) {
			// Query student image list
			$student->images = StudentImage::Where('studentId', $student->id)->get(['imageId']);

			// Query student report list
			$student->reports = Report::Where('studentId', $student->id)->get();

			// For each report, get student name
			foreach ($student->reports as $report) {
				$report->student = Student::Where('id', $report->studentId)->get(['name'])->first();
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

		// Get all teacher reports
		$reports = Report::Where('userId', $user->id)->get();

		return response()->json([
			'message' => "Succesfully fetch teacher resources.",
			'result' 	=> array(
					'managers' 	=> $managers,
					'students' 	=> $students,
					'reports'		=> $reports,
				)
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