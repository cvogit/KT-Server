<?php

namespace App\Http\Controllers;

use App\Payroll;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class PayrollController extends Controller
{
	/**
	 * Create a payroll entry
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response 
	 */
	public function create(Request $request)
	{
		$this->validate($request, [
			'userId' =>	'required|integer',
			'amount' => 'required|integer',
			'payday' => 'date'
			]);

		// Find the user to be added to payroll
		$user = User::where('id', $request->userId)->first();

		if (!$user)
			return response()->json(['message' => "Unable to find user."], 404);

		// Validate user is on payroll
		if (Payroll::where('userId', $user->id)->first())
			return response()->json(['message' => "User is already on payroll."], 404);

		$payroll = Payroll::create([
      'userId' => $request->userId,
      'amount' => $request->amount,
      'payday' => $request->payday
  	]);

  	if (!$payroll)
  		return response()->json(['message' => "Server error, could not add user to payroll."], 500);

		return response()->json([
			'message' => "The user have been added to payroll successfully."
			], 200);
	}

	/**
	 * Return list of payrolls
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response 
	 */
	public function getList(Request $request)
	{
	
		$payrolls = Payroll::get();

		if (!$payrolls)
  		return response()->json(['message' => "Server error, could not get payroll."], 500);

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
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response 
	 */
	public function remove(Request $request, $payrollId)
	{
		// Validate request
		if ( !$this->req->isValidInt($payrollId) )
			return response()->json(['message' => "Invalid payroll."], 404);
		$this->validate($request, [
			'userId' 		=> 'required|integer'
			]);

		// Find the payroll to be remove
		$payroll = Payroll::where('id', $payrollId)->where('userId', $request->userId)->first();

		if (!$payroll)
			return response()->json(['message' => "User is not on payroll."], 404);

		$payroll->delete();

		return response()->json([
			'message' => "The user have been remove from payroll successfully."
			], 200);
	}
}