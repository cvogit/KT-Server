<?php

namespace App\Http\Controllers;

use App\Report;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class ReportController extends Controller
{
	/**
	 * Approve a report
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function approve(Request $request, $reportId)
	{
		$report = Report::find($reportId);

		if ( !$report )
			return response()->json(['message' => "Unable to get report."], 500);

		$report->approve = 1;

		return response()->json([
			'message' => "The report have been approved successfully.",
			'results' => $report
			], 200);
	}

	/**
	 * Create a new report
	 *
	 * @param \Illuminate\Http\Request
	 * @param integer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request, $teacherId)
	{
		// Validate request
		$this->validate($request, [
			'studentId' 		=> 	'required|integer',
			'goal' 					=> 	'required|string',
			'notes'					=>	'string'
			]);

		$user = $this->req->getUser();

		$assigned = TeacherStudent::where('teacherId', $teacherId)->where('studentId', $request->studentId)->first();

		if ( !$assigned )
			return response()->json(['message' => "Teacher does not have access to student."], 404);

		$report = Report::create([
			'teacherId'		=>	$teacherId,
			'studentId'		=>	$request->studentId,
			'goal'				=>	$request->goal,
			'notes'				=>  $request->notes
			]);

		if ( !$report )
			return response()->json(['message' => "Unable to create report."], 500);
		
		return response()->json([
			'message' => 	"The report have been created successfully.",
			'result'	=>	$report
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

	/**
	 * Unapprove a report
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function unapprove(Request $request, $reportId)
	{
		$report = Report::find($reportId);

		if ( !$report )
			return response()->json([
			'message' => "Unable to get report."
			], 500);

		$report->approve = 0;

		return response()->json([
			'message' => "The report have been unapproved successfully.",
			'results' => $reports
			], 200);
	}

}