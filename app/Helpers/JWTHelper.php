<?php

namespace App\Helpers;

use App\User;
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
	 * The Request
	 *
	 * @var \Illuminate\Http\Request
	 */
	private $request;

	public function __construct(JWT $jwt)
	{
		$this->jwt = $jwt;
	}

	/**
	 * Set object request to the current http request
	 *
	 * @param \Illuminate\Http\Request
	 */
	public function setRequest(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Return the user making the request
	 *
	 * @return App\User
	 */
	public function getUser()
	{
		// Decode JWT
		try
		{
			$decoded = $this->jwt->extract($this->request);
		} catch (Exception $e) {
			abort(404, "Invalid Request.");
		}

		// Fetch user using id
		$user = User::where('id', $decoded['jti'])->first();

		return $user;
	}
}