<?php

namespace App\Http\Controllers;

use App\Image;
use App\Manager;
use App\Student;
use App\StudentImage;
use App\Teacher;
use App\TeacherStudent;
use App\User;
use App\UserImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController extends Controller
{
	/**
	 * Upload an image belong to student
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createStudentImage(Request $request, $studentId)
	{
		// Validate request
		if (!$request->getContent()) {
			return response()->json([
				'message' => "Request contents are incorrect."
				], 404);
		}

		if ($request->input('extension'))
			$extension = $request->input('extension');
		else
			$extension = 'jpeg';

		// Store the image in storage
		$image = $request->getContent();
		$fileName   = time() . '_' . mt_rand() . '.' . $extension;
		$imagePath = '/storage/app/images/students/'.$fileName;
		file_put_contents(base_path().$imagePath, $image);

		// Save image path in Image datavase
		$imageObj = Image::create([
			'path'	=> $imagePath
			]);

		// Allocate the image ownership to the student
		$studentImage = StudentImage::create([
			'studentId' 			=> $studentId,
			'imageId'					=> $imageObj->id
			]);

		if (!$studentImage)
			return response()->json([
				'message' => "Server error."
			], 500);

		return response()->json([
			'message' => "The image is uploaded."
			], 200);
	}

	/**
	 * Upload an image belong to user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response 
	 */
	public function createUserImage(Request $request, $userId)
	{
		// Validate request
		if ( !$this->req->isValidInt($userId) )
			return response()->json(['message' => "Invalid id."], 404);

		if (!$request->getContent()) {
			return response()->json([
				'message' => "Request contents are incorrect."
				], 404);
		}

		// Get the image file from base64 or direct image file upload
		$image;
		if($request->has('imageBase64')) {
			$image = $request->imageBase64;
			$image = preg_replace('/^data:image\/[a-z]+;base64,/', '', $image);
			$image = base64_decode(str_replace(' ', '+', $image));
		}
		else {
			$image = $request->getContent();
		}

		if ($request->input('extension')) {
			$extension = $request->input('extension');
		}
		else
			$extension = 'jpeg';

		// Store the image in storage
		$fileName   = time() . '_' . mt_rand() . '.' . $extension;
		$imagePath = '/storage/app/images/users/'.$fileName;
		file_put_contents(base_path().$imagePath, $image);

		// Save image path in Image datavase
		$imageObj = Image::create([
			'path'	=> $imagePath
			]);

		// Find and allocate the image ownership to user making the upload
		$user = $this->req->getUser();

		UserImage::create([
			'userId' 			=> $user->id,
			'imageId'			=> $imageObj->id
			]);

		return response()->json([
			'message' => "The image is uploaded.",
			'result'	=> $imageObj->id,
			], 200);
	}

	/**
	 * Return an image belong to student
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getStudentImage(Request $request, $studentId, $imageId)
	{
		if ( !$this->req->isValidInt($imageId) )
			return response()->json(['message' => "Invalid id."], 404);

		$studentImage = StudentImage::where('studentId', $studentId)->where('imageId', $imageId)->first();

		if ( !$studentImage )
			return response()->json(['message' => "Student dooes not own image."], 404);

		$image = Image::find($studentImage->imageId);
		if (!$image)
			return response()->json(['message' => "The image is no long available."], 500);

		$imgPath = base_path() . $image->path;
		ob_end_clean();
		return response()->download($imgPath);
	}

	/**
	 * Return images ids belong to student
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getStudentImagesList(Request $request, $studentId)
	{
		$ids = StudentImage::where('studentId', $studentId)->get(['imageId']);

		return response()->json([
			'message' => "Successfully return all images ids belong to student.",
			'result' => $ids
			], 200);
	}

	/**
	 * Return an image belong to user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserImage(Request $request, $userId, $imageId)
	{
		// Validate request
		if ( !$this->req->isValidInt($imageId) )
			return response()->json(['message' => "Invalid id."], 404);

		// Validate user have access to the image
		$user = $this->req->getUser();

		$access = UserImage::where('imageId', $imageId)->where('userId', $user->id)->first();										
		if ( !$access )
			return response()->json(['message' => "User does not access to image."], 404);

		$image = Image::find($imageId);
		if (!$image)
			return response()->json(['message' => "The image is no long available."], 500);

		$imgPath = base_path() . $image->path;
		ob_end_clean();
		return response()->download($imgPath);
	}

	/**
	 * Return a list of images ids belong to user
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUserImagesList(Request $request, $userId)
	{
		$user = $this->req->getUser();

		$ids = UserImage::where('userId', $user->id)->get(['imageId']);

		return response()->json([
			'message' => "Successfully return all images ids belong to user.",
			'result' => $ids
			], 200);
	}

	/**
	 * Remove an image belong to a student
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function removeStudentImage(Request $request, $studentId, $imageId)
	{
		// Validate request
		if ( !$this->req->isValidInt($imageId) )
			return response()->json(['message' => "Invalid image id."], 404);

		// Validate image belong to student
		$access = StudentImage::where('studentId', 	$studentId)->where('imageId', 	$imageId)->first();

		if ( !$access )
			return response()->json(['message' => "Student does not have access to image."], 404);

		// Find image data in db
		$image = Image::find($imageId);
		$imagePath = base_path().$image->path;

		// Delete image from storage
		unlink($imagePath);

		// Delete user relationship to image
		$userImg->delete();

		// Delete image entry from database
		$image->delete();

		return response()->json([
			'message' => "Successfully delete image.",
			], 200);
	}

	/**
	 * Remove an image belong to a user
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function removeUserImage(Request $request, $userId, $imageId)
	{
		// Validate request
		if ( !$this->req->isValidInt($imageId) )
			return response()->json(['message' => "Invalid image id."], 404);

		// Validate image belong to user
		$user = $this->req->getUser();
		$access = UserImage::where('userId', 	$user->id)->where('imageId', 	$imageId)->first();

		if ( !$access )
			return response()->json(['message' => "User does not have access to image."], 404);

		// Find image data in db
		$image = Image::find($imageId);
		$imagePath = base_path().$image->path;

		// Delete image from storage
		unlink($imagePath);

		// Delete user access to image
		$access->delete();

		// Delete image entry from database
		$image->delete();

		// If user avatarId is the same as the image being deleted, set avatar to 0
		if( $user->avatarId == $imageId) {
			$user->avatarId = 0;
			$user->save();
		}

		return response()->json([
			'message' => "Successfully delete image.",
			'result'	=> $imageId,
			], 200);
	}
}