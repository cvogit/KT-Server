<?php

namespace App\Http\Controllers;

use App\User;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TeacherController extends Controller
{
	public function __construct()
	{

	}

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

		$teachers = Payment::skip($offset)->take($limit)->get();

		return response()->json([
			'message' => "Succesfully fetch all teachers.",
			'result' 	=> $teachers
			], 200);
	}
}