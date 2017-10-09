<?php

namespace App\Http\Controllers;

use App\User;
use App\Helpers\JWTHelper;
use \Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	/**
	 * The JWT helper
	 *
	 * @var App\Helpers\JWTHelper
	 */
	private $req;

	public function __construct(JWTHelper $req, Request $request)
	{
		$this->req = $req;
		$this->req->setRequest($request);
	}

	/**
	 * Activate user
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function activate(Request $request, $id)
	{

		// Find the user to be activated in db
		$user = User::where('id', $id)->first();
		
		// Return error if can't find user with $id in db
		if( !$user  )
			return response()->json(['message' => "Invalid request, cannot find user."], 404);

		// Check if user is active
		if( $user->active )
			return response()->json(['message' => "The account is already activated."], 404);

		// Activate user and return response 200
		$user->active = 1;
		$user->save();

		return response()->json(['message' => "The account have been activated successfully."], 200);
	}

	/**
	 * Deactivate user
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function deactivate(Request $request, $id)
	{

		// Find user to be deactivated from db
		$user = User::where('id', $id)->first();
		
		// Return error if can't find user with $id in db
		if( !$user  )
			return response()->json(['message' => "Invalid request, cannot find user."], 404);

		// Check if user is active
		if( !$user->active )
			return response()->json(['message' => "The account is already not active."], 404);

		// Deactivate user and return response 200
		$user->active = 0;
		$user->save();

		return response()->json(['message' => "The account has been deactivated successfully."], 200);
	}
}