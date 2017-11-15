<?php

namespace App\Http\Controllers;

use App\User;
use App\UserImage;
use App\Manager;
use App\Teacher;
use App\UserHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class UserController extends Controller
{
	/**
	 * Setting user 'active' field to 1
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function activate(Request $request, $userId)
	{
		if ( !$this->req->isValidInt($userId) )
			return response()->json(['message' => "Invalid id."], 404);
		
		// Find the user to be activated in db
		$user = User::where('id', $userId)->first();

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
	 * @return \Illuminate\Http\Response 
	 */
	public function deactivate(Request $request, $userId)
	{
		if ( !$this->req->isValidInt($userId) )
			return response()->json(['message' => "Invalid id."], 404);

		// Find user to be deactivated from db
		$user = User::where('id', $userId)->first();
		
		// Return error if can't find user with $id in db
		if( !$user  )
			return response()->json(['message' => "Invalid request, cannot find user."], 404);

		// Check if user is active
		if( !$user->active )
			return response()->json(['message' => "The account is already inactive."], 404);

		// Deactivate user
		$user->active = 0;
		$user->save();

		return response()->json([
			'message' => "The account has been deactivated successfully."
			], 200);
	}

	/**
	 * Get an user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(Request $request, $userId)
	{
		if ( !$this->req->isValidInt($userId) )
			return response()->json(['message' => "Invalid user id."], 404);

		$user = User::where('id', $userId)->get(['id', 'name', 'phoneNum', 'lastLogin', 'avatarId', 'active']);

		if( !$user )
			return response()->json(['message' => "Unable to find user."], 404);

		return response()->json([
			'message' => "Succesfully fetch user.",
			'result' 	=> $user
			], 200);
	}

	/**
	 * Get all users
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getList(Request $request)
	{
		$this->validate($request, [
			'status'    => 	'integer|max:1|min:1',
			'offset'		=>	'integer',
		]);

		$status = 1;
		$offset = 0;
		$limit  = 20;

		if ( $request->has('status') )
			$status = $request->input('status');
		if ( $request->has('offset') )
			$offset = $request->input('offset');

		// Get users needed fields
		$users = User::where('active', $status)->skip($offset)->take($limit)->get(['id', 'name', 'phoneNum', 'lastLogin', 'avatarId']);

		return response()->json([
			'message' => "Succesfully fetch all active users.",
			'result' 	=> $users,
			'offset'	=> $offset+$limit   
			], 200);
	}

	/**
	 * Set profile avatar
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function setAvatar(Request $request, $imgId)
	{
		if ( !$this->req->isValidInt($imgId) )
			return response()->json(['message' => "Invalid id."], 404);

		// Check if image exist
		$image = Image::find($imgId);
		if(!$image)
			return response()->json(['message' => "Image does not exist."], 401);

		$user = $this->req->getUser();
		
		// Check if image belong to user
		$access = UserImage::where('userId', $user->id)->where('imageId', $image->id)->first();
		if(!$access)
			return response()->json(['message' => "User does not own the image."], 401);

		$user->avatarId = $image->id;
		$user->save();

		return response()->json([
			'message' => "The user avatar is updated."
			], 200);
	}
	
	/**
	 * Update the user making the request
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$this->validate($request, [
			'email'    	=> 	'email|max:64',
			'name'		 	=>	'string|max:64',
			'password' 	=> 	'confirmed|min:6|max:16',
			'phoneNum' 	=>	'string|min:10|max:10'
		]);

		$user = $this->req->getUser();
		$data = $request->only('email', 'name', 'password', 'phoneNum', 'avatarPath');
		$user->fill($data);
		$user->save();

		return response()->json([
			'message' => "The user is updated."
			], 200);
	}
}