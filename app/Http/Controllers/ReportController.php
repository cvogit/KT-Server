<?php

namespace App\Http\Controllers;

use App\Manager;
use App\Report;
use App\User;
use App\Teacher;
use App\TeacherStudent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class ReportController extends Controller
{
	/**
	 * Create a new report
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
			'studentId' 	=> 	'required|integer'
			]);

		$report = Report::create([
			'userId'		=>	$user->id,
			'studentId'	=>	$request->studentId
			]);

		if ( !$report )
			return response()->json(['message' => "Unable to create report."], 500);

		return response()->json([
			'message' => 	"The report have been created successfully.",
			'result'	=>	$report
			], 200);
	}

	/**
	 * Get a report
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(Request $request, $reportId)
	{
		$user = $this->req->getUser();

		$assigned = Report::where('userId', $user->id)->first();

		$manager = Manager::Where('userId', $user->id)->first();

		if ( !$assigned || !$manager )
			return response()->json(['message' => "User does not have access to student."], 404);

		$report = Report::find($reportId);

		if ( !$report )
			return response()->json([
			'message' => "Unable to get report."
			], 500);

		return response()->json([
			'message' => "The report have been fetch successfully.",
			'results' => $report
			], 200);
	}

	/**
	 * Return reports belong to a student
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getStudentReportsList(Request $request, $studentId)
	{
		$this->validate($request, [
			'offset'		=>	'integer',
		]);

		$offset = 0;
		$limit  = 20;

		if ( $request->has('status') )
			$status = $request->input('status');
		if ( $request->has('offset') )
			$offset = $request->input('offset');


		$reports = Report::where('studentId', $studentId)->skip($offset)->take($limit)->get();

		if ( !$reports )
			return response()->json([
			'message' => "Unable to get reports."
			], 500);

		return response()->json([
			'message' => "The reports have been fetched successfully.",
			'results' => $reports,
			'offset'	=> $offset+$limit 
			], 200);
	}

	/**
	 * Return reports belong to a teacher
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getTeacherReportsList(Request $request, $teacherId)
	{
		$this->validate($request, [
			'offset'		=>	'integer',
		]);

		$offset = 0;
		$limit  = 7;

		if ( $request->has('status') )
			$status = $request->input('status');
		if ( $request->has('offset') )
			$offset = $request->input('offset');

		$reports = Report::where('teacherId', $teacherId)->get();

		if ( !$reports )
			return response()->json([
			'message' => "Unable to get reports."
			], 500);

		return response()->json([
			'message' => "The reports have been fetched successfully.",
			'results' => $reports,
			'offset'	=> $offset+$limit 
			], 200);
	}

	/**
	 * Teacher update a report
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $reportId)
	{
		// Validate request
		$this->validate($request, [
			'goals' 		=> 	'string|max:256',
			'results'		=>	'string|max:256',
			'notes'			=>	'string',
			]);

		$report = Report::find($reportId);

		if ( !$reports )
			return response()->json([
			'message' => "Unable to get report."
			], 500);

		$data = $request->only('goals', 'results', 'notes');
		$report->fill($data);
		$report->update = 1;
		$report->save();

		return response()->json([
			'message' => "The report have been updated successfully."
			], 200);
	}
}