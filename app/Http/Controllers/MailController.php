<?php

namespace App\Http\Controllers;

use App\Mail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MailController extends Controller
{
	/**
	 * Create a new mail
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		// Validate request
		$this->validate($request, [
			'receiverId'	=> 	'required|integer',
			'title'				=>	'required|string',
			'content'			=>	'string',
			]);

		$user = $this->req->getUser();

		if ( !$user->active )
			return response()->json(['mail' => "No user or user is inactive."], 404);

		$receiver = User::Where('id', $request->receiverId)->first();

		if ( !$receiver->active )
			return response()->json(['mail' => "No receiving user or receiver is inactive."], 404);

		$messsage = Mail::create([
			'senderId'		=>	$user->id,
			'receiverId'	=>	$request->receiverId,
			'title'				=>	$request->title,
			'content'			=>  $request->content
			]);

		if ( !$messsage )
			return response()->json(['mail' => "Unable to create messsage."], 500);
		
		return response()->json([
			'message' => 	"The mail have been created successfully.",
			'result'	=>	$messsage
			], 200);
	}

	/**
	 * Return reports belong to a student
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(Request $request)
	{
		$this->validate($request, [
			'offset'		=>	'integer',
		]);

		$offset = 0;
		$limit  = 5;

		if ( $request->has('offset') )
			$offset = $request->input('offset');

		$user = $this->req->getUser();
		
		$mails = Mail::where('receiverId', $user->id)->orderBy('created_at', 'desc')->skip($offset)->take($limit)->select('title', 'content', 'created_at', 'senderId')->get();

		foreach($mails as $mail) {
			$id = $mail->senderId;
			$userName = User::find($id);
			$mail->firstName 	= $userName->firstName;
			$mail->lastName 	= $userName->lastName;
		}

		if ( !$mails )
			return response()->json([
			'mail' => "Unable to get mails."
			], 500);

		return response()->json([
			'message' => "The mails have been fetched successfully.",
			'result' => $mails,
			'offset' => $offset+$limit 
			], 200);
	}

}