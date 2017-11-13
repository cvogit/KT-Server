<?php

namespace App\Http\Middleware;

use App\Manager;
use App\Teacher;
use App\TeacherStudent;
use App\Helpers\RequestHelper;
use Closure;
use Illuminate\Http\Request;

class StudentResourceMiddleware extends Middleware
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
		
		// If user is an active teacher with access to student resources, let them pass
		$teacher = Teacher::where('userId', $user->id)->first();
		if( !$teacher )
			return response()->json(['message' => "Invalid request, no valid user."], 404);
		
		$studentId 	= $request->studentId;
		$access = TeacherStudent::where('teacherId', $teacher->id)->where('studentId', $studentId)->first();

		if ( $access )
				return $next($this->req, $request);

		// if user is not a manager, return error 404
		return response()->json(['message' => "User does not have permission for access."], 404);
	}
}