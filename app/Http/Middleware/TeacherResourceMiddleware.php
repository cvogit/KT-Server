<?php

namespace App\Http\Middleware;

use App\Manager;
use App\Teacher;
use App\Helpers\RequestHelper;
use Closure;
use Illuminate\Http\Request;

class TeacherResourceMiddleware extends Middleware
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
		
		// If user is making a request to their own teacher resources, let them pass
		$teacherId = $request->teacherId;
		$teacher = Teacher::where('userId', $user->id)->first();
		if ( $teacher->id == $teacherId )
				return $next($this->req, $request);

		// if user does not have permission, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}