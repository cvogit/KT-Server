<?php

namespace App\Http\Middleware;

use App\User;
use App\Helpers\RequestHelper;
use Closure;
use Illuminate\Http\Request;

class UserActiveMiddleware extends Middleware
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
		
		return $next($request);
	}
}