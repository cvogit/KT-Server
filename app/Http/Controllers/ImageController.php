<?php

namespace App\Http\Controllers;

use App\Image;
use App\Manager;
use App\User;
use App\UserImage;
use App\Teacher;
use App\Helpers\JWTHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController extends Controller
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
	 * Upload an image belong to user
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return mixed 
	 */
	public function addUserImg(Request $request)
	{
		if (!$request->getContent()) {
			return response()->json([
				'message' => "Request contents are incorrect."
				], 404);
		}

		if (!$request->input('extension')) {
			return response()->json([
				'message' => "Missing content extension."
				], 404);
		}

		// Store the image in storage
		$image = $request->getContent();
		$fileName   = time() . '_' . mt_rand() . '.' . $request->input('extension');
		$imagePath = '/storage/app/images/users/'.$fileName;
		file_put_contents(base_path().$imagePath, $image);

		// Save image path in Image datavase
		$imageObj = Image::create([
			'path'	=> $imagePath
			]);

		// Find and allocate the image ownership to user making the upload
		$user = $this->jwt->getUser();

		UserImage::create([
			'userId' 			=> $user->id,
			'imageId'			=> $imageObj->id
			]);

		return response()->json([
			'message' => "The image is uploaded."
			], 200);
	}

	/**
	 * Return an image belong to user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer $id
	 *
	 * @return mixed 
	 */
	public function getUserImg(Request $request, $imgId)
	{
		// Validate request
		if ( !is_numeric($imgId) || ($imgId < 1) )
			return response()->json(['message' => "Invalid request."], 404);

		// Validate user making request is owner
		$user = $this->jwt->getUser();

		// Validate request user owns the image
		$userImg = UserImage::where('imageId', $imgId)
												->where('userId', $user->id)->first();										
		if (!$userImg)
			return response()->json(['message' => "Invalid request."], 404);

		// Fetch image information 
		$img = Image::find($imgId);

		if (!$img)
			return response()->json(['message' => "The image is no long available."], 500);

		$imgPath = base_path() . $img->path;

		return response()->download($imgPath);
	}

	/**
	 * Return images ids belong to user
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserImgId(Request $request)
	{
		$user = $this->jwt->getUser();

		$ids = UserImage::where('userId', $user->id)->get(['imageId']);

		return response()->json([
			'message' => "Successfully return all images ids belong to user.",
			'result' => $ids
			], 200);
	}

	/**
	 * Remove an image belong to req user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function removeUserImg(Request $request, $imgId)
	{
		// Validate request
		if ( !is_numeric($imgId) || ($imgId < 1) )
			return response()->json(['message' => "Invalid request."], 404);

		// Check if image belong to user makign the delete
		$user = $this->jwt->getUser();
		$userImg = UserImage::where('userId', 	$user->id)
												->where('imageId', 	$imgId)
												->first();
		if ( !$userImg )
			return response()->json(['message' => "Invalid request."], 404);

		return response()->json([
			'message' => "Successfully return all images ids belong to user.",
			'result' => $ids
			], 200);
	}
}