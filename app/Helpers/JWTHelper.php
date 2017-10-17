<?php

namespace App\Helpers;

use App\User;
use \Exception;
use Cvogit\LumenJWT\JWT;
use Illuminate\Http\Request;

class JWTHelper
{
	/**
	 * The JWT factory
	 *
	 * @var \Cvogit\LumenJWT\JWT
	 */
	private $jwt;

	/**
	 * The claims
	 *
	 * @var \Illuminate\Http\Request
	 */
	private $claims;

	public function __construct(JWT $jwt)
	{
		$this->jwt = $jwt;
	}

	/**
	 * Extracts the claims from JWT
	 *
	 */
	public function extractClaims()
	{
		try {
			$this->claims = $this->jwt->extract($this->request);
		} catch (Exception $e) {
			return response()->json(['message' => "Invalid JWT."], 404);
		}
	}

	/**
	 * Set object request to the current http request
	 *
	 * @param \Illuminate\Http\Request
	 */
	public function setRequest(Request $request)
	{
		$this->request = $request;
		$this->extractClaims();
		$this->updateLoginTime();
	}


	/**
	 * Return the user making the request
	 *
	 * @return App\User
	 */
	public function getUser()
	{
		// Fetch user using id
		$user = User::where('id', $this->claims['jti'])->first();

		return $user;
	}

	/**
	 * Update user lastConnectTime
	 *
	 */
	public function updateLoginTime()
	{
		$user = $this->getUser();

		date_default_timezone_set('America/Los_Angeles');
		$user->lastLogin = date('m/d/Y h:i:s a');
		
		$user->save();
	}
}