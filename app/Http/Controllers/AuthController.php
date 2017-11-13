<?php

namespace App\Http\Controllers;

use App\User;
use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Cvogit\LumenJWT\JWT;
use \Exception;

class AuthController extends Controller
{
	/*
	 * The JWT factory
	 *
	 */
	private $jwt;

	public function __construct(JWT $jwt)
	{
		$this->jwt = $jwt;
	}

	/**
	 * Check user login is valid and active
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function login(Request $request)
	{
		// Validate request input
		$this->validate($request, [
			'email'    => 'required|email|max:255',
			'password' => 'required',
		]);

		// Find user from db
		$user = User::where('email', $request->input('email'))->first();

		// Check user exist and correct
		if($user == null)
			return response()->json(['message' => "Incorrect login."], 404);
		if (!Hash::check($request->input('password'), $user->password))
			return response()->json(['message' => "Incorrect login."], 404);
		if (!$user->active)
			return response()->json(['message' => "The account is not activated."], 404);

		// Create and return JWT to user, signed with user id
		try {
			$token = $this->jwt->create($user->id);
		}	catch(\Exception $e){
			return response()->json(['message' => "Could not create JWT, cvogit/lumen-jwt errors."], 404);
    }

    date_default_timezone_set('America/Los_Angeles');
		$user->lastLogin = date('m/d/Y h:i:s a');
		$user->save();
    
		return response()->json(['token' => $token], 200);
	}
}