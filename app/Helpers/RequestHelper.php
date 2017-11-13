<?php

namespace App\Helpers;

use App\User;
use Cvogit\LumenJWT\JWT;
use \Exception;
use Illuminate\Http\Request;


class RequestHelper
{

	/**
	 * The claims
	 *
	 * @var json
	 */
	private $claims;

	/**
	 * The JWT
	 *
	 * @var \Cvogit\LumenJWT\JWT
	 */
	private $jwt;

	/**
	 * The request
	 *
	 * @var \Illuminate\Http\Request
	 */
	private $request;

	/**
	 * The user
	 *
	 * @var \App\User
	 */
	private $user;

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
	 * Validate positive integer
	 *
	 * @param integer
	 *
	 * @return boolean
	 */
	public function isValidInt($int)
	{
		if ( !is_numeric($int) || ($int < 1) )
			return false;
		return true;
	}


	/**
	 * Return the user making the request
	 *
	 * @return App\User
	 */
	public function getUser()
	{

		return $this->user;
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
		$this->setUser();
		$this->updateLoginTime();
	}

	/**
	 * Set user to the user making http request
	 *
	 */
	public function setUser()
	{
		$this->user = User::where('id', $this->claims['jti'])->first();
	}

	/**
	 * Update user lastConnectTime
	 *
	 */
	public function updateLoginTime()
	{
		$user = $this->getUser();

		date_default_timezone_set('America/Los_Angeles');
		$user->lastLogin 	= date('m/d/Y h:i:s a');
		
		$user->save();
	}
}