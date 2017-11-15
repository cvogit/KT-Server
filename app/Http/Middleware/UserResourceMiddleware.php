<?php

namespace App\Http\Middleware;

use App\User;
use App\Helpers\RequestHelper;
use Closure;
use Illuminate\Http\Request;

class UserResourceMiddleware extends Middleware
{
	public function __construct(RequestHelper $req, Request $request)
	{
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
		$user 			= $this->req->getUser();

		if(!$user->active)
			return response()->json(['message' => "Invalid request, no valid user."], 404);
		
		// If user is a manager, let them pass
		$manager = Manager::where('userId', $user->id)->first();
		if( $manager )
			return $next($this->req, $request);
		
		// If user is making a request to their own resources, let them pass
		$userId 	= $request->userId;
		if ( $user->id == $userId )
				return $next($this->req, $request);

		// if user is not a manager, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}