<?php

namespace App\Http\Middleware;

use App\User;
use App\Manager;
use App\Helpers\RequestHelper;
use Closure;
use Illuminate\Http\Request;

class UserResourceMiddleware extends Middleware
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
		$user 			= $this->req->getUser();

		if(!$user->active)
			return response()->json(['message' => "Invalid request, no valid user."], 404);
		
		// If user is a manager, let them pass
		$manager = Manager::where('userId', $user->id)->first();
		if( $manager )
			return $next($request);
		
		// If user is making a request to their own resources, let them pass
		$userId 	= $request->route()[2]['userId'];
		if ( $user->id == $userId )
				return $next($request);

		// if user does not have permission, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}