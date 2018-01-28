<?php

namespace App\Http\Controllers;

use App\User;
use App\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;



class AnnouncementController extends Controller
{
	/**
	 * Create a new announcement
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		// Validate request
		$validator = $this->announcementValidator($request->all());
		
		if( $validator->fails() )
			return response()->json([
			'message' => (string) $validator->messages()
			], 422);

		$userId = $this->req->getUser()->id;

		$announcement = Announcement::create([
				'active' 	=> 1,
				'userId' 	=> $userId,
				'title'  	=> $request->input('title'),
				'content'	=> $request->input('content'),
			]);

  	if (!$announcement)
  		return response()->json(['message' => "Server error."], 500);

		return response()->json([
			'message' => 'The announcement has been created successfully.'
			], 200);
	}

	/**
	 * Return an announcement
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(Request $request, $announcementId)
	{	
		// Validate $id
		if ( !$this->req->isValidInt($announcementId) )
			return response()->json(['message' => 'The request parameters are invalid.'], 404);

		// Find announcement to be return
		$announcement = Announcement::find($announcementId);

		if ( !$announcement )
			return response()->json(['message' => 'Unable to find announcement.'], 404);

		return response()->json([
			'message' => "Succesfully fetch announcement.",
			'result' 	=> $announcement
			], 200);
	}

	/**
	 * Return all active announcement
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getList(Request $request)
	{	
		$offset = 0;
		$limit  = 5;

		if ( $request->has('offset') )
			$offset = $request->input('offset');

		$announcements = Announcement::where('active', 1)->skip($offset)->take($limit)->select('title', 'content', 'created_at', 'updated_at', 'userId')->get();

		foreach($announcements as $announcement) {
			$id = $announcement->userId;
			$userName = User::find($id);
			$announcement->firstName 	= $userName->firstName;
			$announcement->lastName 	= $userName->lastName;
		}

		$totalAnnouncement = Announcement::where('active', 1)->count();

		return response()->json([
			'message' => "Succesfully fetch announcements.",
			'result' 	=> $announcements,
			'offset'	=> $offset+$limit,
			'total'   => $totalAnnouncement,
			], 200);
	}

	/**
	 * Update an announcement
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $announcementId)
	{	
		// Validate $id
		if ( !$this->req->isValidInt($announcementId) )
			return response()->json(['message' => 'The request parameters are invalid.'], 404);

		$validator = $this->announcementValidator($request->all());
		if ( $validator->fails() )
			return response()->json([
			'message' => (string) $validator->messages()
			], 422);

		// Find the announcement
		$announcement = Announcement::find($announcementId);

		if ( !$announcement )
			return response()->json(['message' => 'Unable to find announcement.'], 404);

		if( $request->has('active') )
			$announcement->active 		= $request->input('active');
		$announcement->title 		= $request->input('title');
		$announcement->content 	= $request->input('content');
		$announcement->save();

		return response()->json([
			'message' => "Succesfully update announcement.",
			], 200);
	}

	/**
	 * Validate annuncement
	 * @param array
	 * @return boolean
	 */
	public function announcementValidator(array $data)
	{

		return Validator::make($data, [
			'active'			=> 'min:0|max:1',
			'title'       => 'required|string|min:6|max:128',
			'content' 		=> 'required|string|min:0|max:65535'
		]);
	}
}