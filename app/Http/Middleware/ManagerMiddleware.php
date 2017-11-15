<?php

namespace App\Http\Middleware;

use App\Manager;
use Closure;
use \Exception;
use Illuminate\Http\Request;

class ManagerMiddleware extends Middleware
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
		$user = $this->req->getUser();
		if(!$user->active)
			return response()->json(['message' => "Invalid request, user is not a manager."], 404);

		// If user is a manager, let the request pass
		if( Manager::where('userId', $user->id)->first() )
			return $next($request);

		// if user is not a manager, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}
