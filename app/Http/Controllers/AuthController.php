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
		$validator = $this->loginValidator($request->all());
		
		if($validator->fails())
			return response()->json([
			'message' => (string) $validator->messages()
			], 422);
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
		} catch(\Exception $e){
			return response()->json(['message' => "Could not create JWT, cvogit/lumen-jwt errors."], 404);
		}

		date_default_timezone_set('America/Los_Angeles');
		$user->lastLogin = date('m/d/Y h:i:s a');
		$user->save();

		return response()->json([
			'message' => "Login Successful.",
			'result'  => [
				'token' => $token,
				'userId'=> $user->id,
			]
		], 200);
	}

	/**
	 * Validate user inputs
	 * @param array
	 * @return boolean
	 */
	public function loginValidator(array $data)
	{

		return Validator::make($data, [
			'email' 		=> 'required|email',
			'password'  => 'required|string|min:6',
		]);
	}
}