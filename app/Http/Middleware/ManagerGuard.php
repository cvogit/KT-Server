<?php

namespace App\Http\Middleware;

use App\Manager;
use App\Helpers\JWTHelper;
use Closure;
use \Exception;
use Illuminate\Http\Request;

class ManagerGuard
{
	/**
	 * The JWT helper
	 *
	 * @var App\Helpers\JWTHelper
	 */
	private $jwt;
	
	/**
	 * Create a new middleware instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Factory  $auth
	 * @return void
	 */
	public function __construct(JWTHelper $jwt, Request $request)
	{
		$this->jwt = $jwt;
		$this->jwt->setRequest($request);
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		//Fetch user from request
		$user = $this->jwt->getUser();

		if($user == null)
			return response()->json(['message' => "Invalid request, user is not a manager."], 404);

		// If user is a manager, let the request pass
		if( Manager::where('userId', $user->id)->first() )
				return $next($request);

		// if user is not a manager, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}
