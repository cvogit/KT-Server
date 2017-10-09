<?php

namespace App\Http\Controllers;

use App\User;
use App\Helpers\RequestHelper;
use Auth;
use \Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Cvogit\LumenJWT\JWT;


class ManagerController extends Controller
{
	/**
	 * The request helper
	 *
	 * @var App\Helpers\RequestHelper
	 */
	private $req;

	public function __construct(RequestHelper $req, Request $request)
	{
		$this->req = $req;
		$this->req->setRequest($request);
	}

	/**
	 * Activate user
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function activate(Request $request)
	{
		$this->validate($request, [
			'userId'    => 'required|integer|max:255'
		]);
		
		// Find user to be activated from db
		$user = User::where('id', $request->input('userId'))->first();

		// Check if user is activated
		if($user->active)
			return response()->json(['message' => "The account is already activated."], 404);

		// Activate user and return response 200
		$user->active = 1;
		$user->save();

		return response()->json(['message' => "The account have been activated successfully."], 200);
	}

	/**
	 * Deactivate user
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function deactivate(Request $request)
	{
		$this->validate($request, [
			'userId'    => 'required|integer|max:255'
		]);
		
		// Find user to be activated from db
		$user = User::where('id', $request->input('userId'))->first();

		// Check if user is activated
		if(!$user->active)
			return response()->json(['message' => "The account is already deactivated."], 404);

		// Activate user and return response 200
		$user->active = 0;
		$user->save();

		return response()->json(['message' => "The account has been deactivated successfully."], 200);
	}

	/**
	 * Return all users that are teacher on payroll
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function teachers(Request $request)
	{	
		// Find user to be activated from db
		$user = User::where('id', $request->input('userId'))->first();

		// Check if user is activated
		if(!$user->active)
			return response()->json(['message' => "The account is already deactivated."], 404);

		// Activate user and return response 200
		$user->active = 0;
		$user->save();

		return response()->json(['message' => "The account has been deactivated successfully."], 200);
	}
}