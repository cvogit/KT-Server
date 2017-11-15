<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Payroll;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class PaymentController extends Controller
{
	/**
	 * Log a payment to a user to database
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response 
	 */
	public function create(Request $request)
	{	
		$this->validate($request, [
			'amount' 	=> 'required|integer',
			'date' 		=> 'required|date',
			'userId'  => 'required|integer'
			]);

		// Find the user to make payment to
		$userObj = User::where('id', $request->userId)->first();

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
			return response()->json(['message' => "A similar payment was already made, please contact administrator if another is needed."], 500);

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
	 * Return a payment
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(Request $request, $paymentId)
	{
		// Validate request
		if ( !$this->req->isValidInt($paymentId) )
			return response()->json(['message' => "Invalid id."], 404);

		$payment = Payment::find($paymentId);

		if (!$payment)
  		return response()->json(['message' => "Server error, could not get payment."], 500);

		return response()->json([
			'message' 	=> "Payments are fetched successfully.",
			'result'		=> $payment,
			'offset'		=> $offset+$limit
			], 200);
	}

	/**
	 * Return a list of payments with offset and limit, available to manager
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getList(Request $request)
	{
		// Validate request
		$this->validate($request, [
			'offset' 		=> 'integer',
			]);

		$offset = 0;
		$limit  = 20;

		if ( $request->has('offset') )
			$offset = $request->input('offset');

		$payments = Payment::skip($offset)->take($limit)->get();

		if (!$payments)
  		return response()->json(['message' => "Server error, could not get payments."], 500);

		return response()->json([
			'message' 	=> "Payments are fetched successfully.",
			'result'		=> $payments,
			'offset'		=> $offset+$limit
			], 200);
	}

	/**
	 * Return a payment belong to a user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserPayment(Request $request, $userId, $paymentId)
	{
		// Validate request
		if ( !$this->req->isValidInt($userId) )
			return response()->json(['message' => "Invalid user id."], 404);
		if ( !$this->req->isValidInt($paymentId) )
			return response()->json(['message' => "Invalid payment id."], 404);

		$payment = Payment::find($paymentId);

		if (!$payment)
  		return response()->json(['message' => "Server error, could not get payment."], 500);

		return response()->json([
			'message' 	=> "Payments are fetched successfully.",
			'result'		=> $payment
			], 200);
	}

	/**
	 * Return a list of payments belong to a user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserPaymentsList(Request $request, $userId)
	{
		// Validate request
		if ( !$this->req->isValidInt($userId) )
			return response()->json(['message' => "Invalid user id."], 404);

		// Validate request
		$this->validate($request, [
			'offset' 		=> 'integer',
			]);

		$offset = 0;
		$limit  = 20;

		if ( $request->has('offset') )
			$offset = $request->input('offset');

		$payments = Payment::where('userId', $userId)->skip($offset)->take($limit)->get();

		if (!$payments)
  		return response()->json(['message' => "Server error, could not get payments."], 500);

		return response()->json([
			'message' 	=> "Payments are fetched successfully.",
			'result'		=> $payments,
			'offset'		=> $offset+$limit
			], 200);
	}

}