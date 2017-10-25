<?php

namespace App\Http\Controllers;

use App\Payroll;
use App\User;
use App\Helpers\JWTHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class PayrollController extends Controller
{
	/**
	 * The JWT helper
	 *
	 * @var App\Helpers\JWTHelper
	 */
	private $jwt;

	public function __construct(JWTHelper $jwt, Request $request)
	{
		$this->jwt = $jwt;
		$this->jwt->setRequest($request);
	}

	/**
	 * Add user to payroll
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return mixed 
	 */
	public function add(Request $request, $id)
	{
		// Validate request
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => "Invalid request."], 404);
		
		$this->validate($request, [
			'amount' => 'required|integer',
			'payday' => 'date'
			]);

		// Find the user to be added to payroll
		$user = User::where('id', $id)->first();

		if (!$user)
			return response()->json(['message' => "Invalid request."], 404);

		if (Payroll::where('userId', $user->id)->first())
			return response()->json(['message' => "User is already on payroll."], 404);

		$payroll = Payroll::create([
      'userId' => $user->id,
      'amount' => $request->amount,
      'payday' => $request->payday
  	]);

  	if (!$payroll)
  		return response()->json(['message' => "Server error."], 500);

		return response()->json([
			'message' => "The user have been added to payroll successfully."
			], 200);
	}

	/**
	 * Return list of payrolls
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return mixed 
	 */
	public function get(Request $request)
	{
		$payrolls = Payroll::get();

		return response()->json([
			'message' => "Payrolls are fetched successfully.",
			'result'	=> $payrolls
			], 200);
	}

	/**
	 * Remove user from payroll
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return mixed 
	 */
	public function remove(Request $request, $id)
	{
		// Validate request
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => "Invalid request."], 404);

		// Find the payroll to be remove
		$payroll = Payroll::where('userId', $id)->first();

		if (!$payroll)
			return response()->json(['message' => "User is not on payroll."], 404);

		$payroll->delete();

		return response()->json([
			'message' => "The user have been remove from payroll successfully."
			], 200);
	}


}