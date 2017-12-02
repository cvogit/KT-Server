<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;

class UserPrivateMiddleware extends Middleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$request->attributes->add(['req' => $this->req]);

		//Fetch user from request
		$user 		= $this->req->getUser();

		if(!$user->active)
			return response()->json(['message' => "User does not have permission for access."], 404);

		$userId 	= $request->route()[2]['userId'];
		// If user is making a request to their own resources, let them pass
		if ( $user->id == $userId )
			return $next($request);

		// if user is not a manager, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}