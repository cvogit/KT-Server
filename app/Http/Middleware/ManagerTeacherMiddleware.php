<?php

namespace App\Http\Middleware;

use App\Manager;
use App\Teacher;
use App\Helpers\RequestHelper;
use Closure;
use Illuminate\Http\Request;

class ManagerTeacherMiddleware extends Middleware
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

		// If user is a manager or a teacher let them pass
		$manager = Manager::where('userId', $user->id)->first();
		if ( $manager )
			return $next($request);
		
		$teacher = Teacher::where('userId', $user->id)->first();
		if ( $teacher )
			return $next($request);

		// if user does not have permission, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}