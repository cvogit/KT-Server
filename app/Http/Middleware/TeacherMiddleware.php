<?php

namespace App\Http\Middleware;

use App\Teacher;
use App\Helpers\RequestHelper;
use Closure;
use \Exception;
use Illuminate\Http\Request;

class TeacherGuard extends Middleware
{
	public function __construct()
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
		$user = $this->req->getUser();

		if(!$user->active)
			return response()->json(['message' => "Invalid request, user is not a manager."], 404);

		// If user is a teacher, let the request pass
		if( Teacher::where('userId', $user->id)->first() )
				return $next($this->req, $request);

		// if user is not a manager, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}
