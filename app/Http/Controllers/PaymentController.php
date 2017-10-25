<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Payroll;
use App\User;
use App\Helpers\JWTHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class PaymentController extends Controller
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
	 * Log a payment to a user to database
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
			'amount' 	=> 'required|integer',
			'date' 		=> 'date'
			]);

		// Find the user to be added to payroll
		$userObj = User::where('id', $id)->first();

		if (!$userObj)
			return response()->json(['message' => "Invalid request."], 404);

		// Check if user is on payroll
		if (!Payroll::where('userId', $userObj->id)->first())
			return response()->json(['message' => "User is not on payroll."], 404);

		// Check if a payment is already made to user today with the same amount
		if (	Payment::where('userId', $userObj->id)
								 ->where('amount', $request->amount)
								 ->where('date', 	 $request->date)
								 ->first())
			return response()->json(['message' => "A similar payment was already made, please contact administrator if another is needed."], 404);

		// Log the payment
		$payment = Payment::create([
      'userId' 	=> $userObj->id,
      'amount' 	=> $request->amount,
      'date' 		=> $request->date
  	]);

  	if (!$payment)
  		return response()->json(['message' => "Server error."], 500);

		return response()->json([
			'message' => "The payment have been logged successfully."
			], 200);
	}

	/**
	 * Return a list of payments with offset and limit
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return mixed 
	 */
	public function get(Request $request)
	{
		// Validate request have parameters
		$offset = $request->offset;
		$limit 	= $request->limit;

		if ( !( is_numeric($offset) && is_numeric($limit) ) )
			return response()->json(['message' => "Invalid request."], 404);

		$payments = Payment::skip($offset)->take($limit)->get();

		return response()->json([
			'message' 	=> "Payments are fetched successfully.",
			'result'		=> $payments,
			'offset'		=> $offset+$limit
			], 200);
	}

	/**
	 * Return a list of payments belong to a user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return mixed 
	 */
	public function getUser(Request $request, $id)
	{
		// Validate request
		if ( !is_numeric($id) || ($id < 1) )
			return response()->json(['message' => "Invalid request."], 404);

		// Validate request
		$offset = $request->offset;
		$limit 	= $request->limit;

		if ( !( is_numeric($offset) && is_numeric($limit) ) )
			return response()->json(['message' => "Invalid request."], 404);

		$payments = Payment::where('userId', $id)->skip($offset)->take($limit)->get();

		return response()->json([
			'message' 	=> "Payments are fetched successfully.",
			'result'		=> $payments,
			'offset'		=> $offset+$limit
			], 200);
	}
}