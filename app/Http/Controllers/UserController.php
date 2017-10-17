<?php

namespace App\Http\Controllers;

use App\User;
use App\User_Image;
use App\Manager;
use App\Teacher;
use App\UserHelpers;
use App\Helpers\JWTHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
	 * Setting user 'active' field to 1
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function activate(Request $request, $id)
	{
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => "Invalid request."], 404);
		
		// Find the user to be activated in db
		$user = User::where('id', $id)->first();

		// Return error if can't find user with $id in db
		if( !$user  )
			return response()->json(['message' => "Invalid request, cannot find user."], 404);

		// Check if user is active
		if( $user->active )
			return response()->json(['message' => "The account is already activated."], 404);

		// Activate user
		$user->active = 1;
		$user->save();

		return response()->json([
			'message' => "The account have been activated successfully."
			], 200);
	}

	/**
	 * Setting user 'active' field to 0
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function deactivate(Request $request, $id)
	{
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => "Invalid request."], 404);

		// Find user to be deactivated from db
		$user = User::where('id', $id)->first();
		
		// Return error if can't find user with $id in db
		if( !$user  )
			return response()->json(['message' => "Invalid request, cannot find user."], 404);

		// Check if user is active
		if( !$user->active )
			return response()->json(['message' => "The account is already not active."], 404);

		// Deactivate user
		$user->active = 0;
		$user->save();

		return response()->json([
			'message' => "The account has been deactivated successfully."
			], 200);
	}

	/**
	 * Get an active user
	 *
	 * @param \Illuminate\Http\Request
	 * @param numeric
	 *
	 * @return mixed 
	 */
	public function getUser(Request $request, $id)
	{
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => "Invalid request."], 404);

		$user = User::find($id);

		if( !$user || !$user->active )
			return response()->json(['message' => "Invalid user."], 404);

		$user = User::where('id', $id)
							->get(['id', 'name', 'phoneNum', 'lastLogin'])->first();

		// If user is a teacher, fetch teach id
		// if ( $teacher = Teacher::where('userId', $user->id)->first() )
		// 		$user->teacherId = $teacher->id;
		// 	else
		// 		$user->teacherId = "";

		return response()->json([
			'message' => "Succesfully fetch user.",
			'result' => $user
			], 200);
	}

	/**
	 * Get all active user
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function getUsers(Request $request)
	{
		// Get active users needed fields
		$users = User::where('active', 1)
							->get(['id', 'name', 'phoneNum', 'lastLogin']);

		return response()->json([
			'message' => "Succesfully fetch all users.",
			'result' => $users     // Question Ask about this: consistency vs efficiency
			], 200);
	}

	/**
	 * Upload an image belong to user
	 *
	 * @param \Illuminate\Http\Request
	 * @param string
	 *
	 * @return mixed 
	 */
	public function uploadImage(Request $request)
	{
		if (!$request->hasFile('image')) {
			return response()->json([
				'message' => "Invalid request, inputs are incorrect."
				], 404);
		}

		// Save image in storage
		$image = $request->file('image');

		$fileName   = $this->jwt->getUser()->name . '_' . time() . '.' . $image->getClientOriginalExtension();

		$imagePath = base_path().'/storage/app/images/users/'.$fileName;
		
		file_put_contents($imagePath, $image);

		// Allocate the image ownership to user
		$user = $jwt->getUser();

		User_Image::create([
			'userId' 			=> $user->id,
			'imagePath'		=> $imagePath
			]);

		return response()->json([
			'message' => "The image is uploaded."
			], 200);
	}
	
	/**
	 * Update an active user
	 *
	 * @param \Illuminate\Http\Request
	 * @param string
	 *
	 * @return mixed 
	 */
	public function updateUser(Request $request, $id)
	{
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => "Invalid request."], 404);

		// Only a manager or the user themselves can update
		$reqId = $this->jwt->getUser()->id;
		
		// Check if a manger is making the request
		if( !Manager::where('userId', $reqId)->first() )
		{
			// Check if user is making the request on themselves
			if( Teacher::where('userId', $reqId)->first()->userId != $id )
				return response()->json(['message' => "Permission denied to update user."], 404);
		}

		$data = $request->only('email', 'name', 'password', 'phoneNum');
	
		$user = User::find($id);

		$user->fill($data);
		$user->save();

		return response()->json([
			'message' => "The user is updated."
			], 200);
	}
}