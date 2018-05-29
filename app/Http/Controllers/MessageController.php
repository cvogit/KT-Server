<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MessageController extends Controller
{
	/**
	 * Create a new message
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
			return response()->json(['message' => "No user or user is inactive."], 404);

		$receiver = User::Where('id', $request->receiverId)->first();

		if ( !$receiver->active )
			return response()->json(['message' => "No receiving user or receiver is inactive."], 404);

		$messsage = Message::create([
			'senderId'		=>	$user->id,
			'receiverId'	=>	$request->receiverId,
			'title'				=>	$request->title,
			'content'			=>  $request->content
			]);

		if ( !$messsage )
			return response()->json(['message' => "Unable to create messsage."], 500);
		
		return response()->json([
			'message' => 	"The message have been created successfully.",
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
		
		$messages = DB::table('messages')
												->where('receiverId', $user->id)
                        ->join('users', 'messages.receiverId', '=', 'users.id')
                        ->select('messages.*', 'users.firstName', 'users.lastName')
                        ->skip($offset)
                        ->take($limit)
                        ->get();

		if ( !$messages )
			return response()->json([
			'message' => "Unable to get messages."
			], 500);

		return response()->json([
			'message' => "The messages have been fetched successfully.",
			'results' => $messages,
			'offset'	=> $offset+$limit 
			], 200);
	}

}