<?php

namespace App\Http\Controllers;

use App\User;
use App\UserImage;
use App\Manager;
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
	 * @param string
	 *
	 * @return mixed 
	 */
	public function userUpload(Request $request)
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

		// Allocate the image ownership to user
		$user = $this->jwt->getUser();

		// Save image in storage
		$image = $request->getContent();

		$fileName   = $user->id . '_' . time() . '.' . $request->input('extension');

		$imagePath = base_path().'/storage/app/images/users/'.$fileName;

		file_put_contents($imagePath, $image);

		UserImage::create([
			'userId' 			=> $user->id,
			'imagePath'		=> $fileName
			]);

		return response()->json([
			'message' => "The image is uploaded."
			], 200);
	}
}