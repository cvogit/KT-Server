<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Cvogit\LumenJWT\JWT;

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

		// Create and return JWT to user
		$token = $this->jwt->create("fake");

		return response()->json(['token' => $token], 200);
	}
}