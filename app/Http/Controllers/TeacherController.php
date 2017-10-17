<?php

namespace App\Http\Controllers;

use App\User;
use App\Teacher;
use App\Helpers\JWTHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Cvogit\LumenJWT\JWT;


class TeacherController extends Controller
{
	/**
	 * The JWT helper
	 *
	 * @var App\Helpers\JWTHelper
	 */
	private $jwt;

	public function __construct(JWTHelper $jwt, Request $request)
	{
		$this->jwt = $jwt;
		$this->jwt->setRequest($request);
	}

	/**
	 * Create a new entry to the teacher table from an existing user id
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return mixed 
	 */
	public function add(Request $request, $id)
	{
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => 'Invalid request.'], 404);

		// Find the user to be activated in db
		$user = User::find($id);

		// Return error if can't find user with $id in db
		if( !$user || !$user->active  )
			return response()->json(['message' => 'Invalid request, user does not exist or is not active.'], 404);

		// Check if user is already in teacher table
		if( Teacher::where('userId', $user->id)->first() )
			return response()->json(['message' => 'The user is already a teacher.'], 404);

		Teacher::create([
      'userId' => $user->id,
  	]);

		return response()->json([
			'message' => 'The teacher have been added successfully.'
			], 200);
	}

	/**
	 * Remove an entry from the teacher table
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return mixed 
	 */
	public function remove(Request $request, $id)
	{
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => 'Invalid request.'], 404);

		// Find teacher to be remove
		$teacher = Teacher::find($id);

		// Return error if can't find user with $id in db
		if( !$teacher )
			return response()->json(['message' => 'Invalid request, the teacher does not exist.'], 404);

		$teacher->delete();

		return response()->json([
			'message' => 'The teacher have been removed successfully.'
			], 200);
	}

	/**
	 * Return a teacher
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function getTeacher(Request $request, $id)
	{	
		// Validate $id
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => 'The request parameters are invalid.'], 404);

		// Find teacher to be return
		$teacher = Teacher::where('id', $id)->get(['numStudents'])->first();

		// Return error if can't find user with $id in db
		if( !$teacher )
			return response()->json(['message' => 'Invalid request, the teacher does not exist.'], 404);

		// Get the teacher user info
		$user = User::where('id', $id)->get(['lastLogin'])->first();

		$result = [
			'numStudents' => $teacher->numStudents, 
			'lastLogin' => $user->lastConnectTime
			];

		return response()->json([
			'message' => "Succesfully fetch teacher.",
			'result' => $result
			], 200);
	}

	/**
	 * Return all teachers informations
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function getTeachers(Request $request)
	{	
		// Get all teachers user id
		$ids = Teacher::all()->pluck('userId');

		// Get the teacher user info
		$users = User::find($ids, array('id', 'name', 'email', 'phoneNum', 'lastLogin'));

		foreach($users as $user)
		{
			$user->numStudents = Teacher::where('userId', $user->id)->first()->numStudents;
		}

		return response()->json([
			'message' => "Succesfully fetch all users.",
			'result' => $users     // Question Ask about this: consistency vs efficiency
			], 200);
	}
}