<?php

namespace App\Http\Controllers;

use App\User;
use App\Student;
use App\BasicForm;
use App\PregnancyForm;
use App\BirthForm;
use App\InfancyForm;
use App\ToddlerForm;
use App\FamilyForm;
use App\IllnessForm;
use App\EducationForm;
use App\PresentForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class FormController extends Controller
{
	/**
	 * Update student basic form (1)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateBasicForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the basic form
		$form = BasicForm::find($student->basicFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}

		$form->name 			= $request->input('name');
		$form->birthday 	= $request->input('birthday');
		$form->male 			= $request->input('male');
		$form->female 		= $request->input('female');
		$form->birthplace = $request->input('birthplace');
		$form->address 		= $request->input('address');
		$form->phone1 		= $request->input('phone1');
		$form->phone2 		= $request->input('phone2');
		$form->email 			= $request->input('email');
		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}

	/**
	 * Update student pregnancy form (2)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updatePregnancyForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the pregnancy form
		$form = PregnancyForm::find($student->pregnancyFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}
		
		$form->question_1 = $request->input('question_1');
		$form->question_2 = $request->input('question_2');
		$form->question_3 = $request->input('question_3');
		$form->question_4 = $request->input('question_4');
		$form->question_5 = $request->input('question_5');
		$form->question_6 = $request->input('question_6');
		$form->question_7 = $request->input('question_7');
		$form->question_8 = $request->input('question_8');
		$form->question_9 = $request->input('question_9');
		$form->question_10 = $request->input('question_10');
		$form->question_11 = $request->input('question_11');
		$form->question_12 = $request->input('question_12');
		$form->question_13 = $request->input('question_13');
		$form->question_14 = $request->input('question_14');
		$form->question_15 = $request->input('question_15');
		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}

	/**
	 * Update student birth form (3)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateBirthForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the birth form
		$form = BirthForm::find($student->birthFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}
		
		$form->question_1 = $request->input('question_1');
		$form->question_2 = $request->input('question_2');
		$form->question_3 = $request->input('question_3');
		$form->question_4 = $request->input('question_4');
		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}

	/**
	 * Update student infancy form (4)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateInfancyForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the infancy form
		$form = InfancyForm::find($student->infancyFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}
		
		$form->question_1 = $request->input('question_1');
		$form->question_2 = $request->input('question_2');
		$form->question_3 = $request->input('question_3');
		$form->question_4 = $request->input('question_4');
		$form->question_5 = $request->input('question_5');
		$form->question_6 = $request->input('question_6');
		$form->question_7 = $request->input('question_7');
		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}

	/**
	 * Update student toddler form (5)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateToddlerForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the toddler form
		$form = ToddlerForm::find($student->toddlerFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}
		
		$form->question_1 = $request->input('question_1');
		$form->question_2 = $request->input('question_2');
		$form->question_3 = $request->input('question_3');
		$form->question_4 = $request->input('question_4');
		$form->question_5 = $request->input('question_5');
		$form->question_6 = $request->input('question_6');
		$form->question_7 = $request->input('question_7');
		$form->question_8 = $request->input('question_8');
		$form->question_9 = $request->input('question_9');
		$form->question_10 = $request->input('question_10');
		$form->question_11 = $request->input('question_11');
		$form->question_12 = $request->input('question_12');
		$form->question_13 = $request->input('question_13');
		$form->question_14 = $request->input('question_14');
		$form->question_15 = $request->input('question_15');
		$form->question_10 = $request->input('question_16');
		$form->question_11 = $request->input('question_17');
		$form->question_12 = $request->input('question_18');
		$form->question_13 = $request->input('question_19');
		$form->question_14 = $request->input('question_20');
		$form->question_15 = $request->input('question_21');
		$form->question_15 = $request->input('question_22');

		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}

	/**
	 * Update student family form (6)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateFamilyForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the family form
		$form = FamilyForm::find($student->familyFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}
		
		$form->question_1 = $request->input('question_1');
		$form->question_2 = $request->input('question_2');
		$form->question_3 = $request->input('question_3');
		$form->question_4 = $request->input('question_4');
		$form->question_5 = $request->input('question_5');
		$form->question_6 = $request->input('question_6');
		$form->question_7 = $request->input('question_7');
		$form->question_8 = $request->input('question_8');
		$form->question_9 = $request->input('question_9');
		$form->question_10 = $request->input('question_10');
		$form->question_11 = $request->input('question_11');
		$form->question_12 = $request->input('question_12');

		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}

	/**
	 * Update student family form (7)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateIllnessForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the family form
		$form = IllnessForm::find($student->illnessFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}
		
		$form->question_1 = $request->input('question_1');
		$form->question_2 = $request->input('question_2');
		$form->question_3 = $request->input('question_3');
		$form->question_4 = $request->input('question_4');
		$form->question_5 = $request->input('question_5');
		$form->question_6 = $request->input('question_6');
		$form->question_7 = $request->input('question_7');
		$form->question_8 = $request->input('question_8');
		$form->question_9 = $request->input('question_9');
		$form->question_10 = $request->input('question_10');
		$form->question_11 = $request->input('question_11');
		$form->question_12 = $request->input('question_12');
		$form->question_10 = $request->input('question_13');
		$form->question_11 = $request->input('question_14');
		$form->question_12 = $request->input('question_15');
		$form->question_10 = $request->input('question_16');
		$form->question_11 = $request->input('question_17');
		$form->question_12 = $request->input('question_18');
		$form->question_10 = $request->input('question_19');
		$form->question_11 = $request->input('question_20');
		$form->question_12 = $request->input('question_21');
		$form->question_12 = $request->input('question_22');
		$form->question_10 = $request->input('question_23');
		$form->question_11 = $request->input('question_24');
		$form->question_12 = $request->input('question_25');
		$form->question_10 = $request->input('question_26');
		$form->question_11 = $request->input('question_27');
		$form->question_12 = $request->input('question_28');
		$form->question_10 = $request->input('question_29');
		$form->question_11 = $request->input('question_30');
		$form->question_12 = $request->input('question_31');
		$form->question_10 = $request->input('question_32');
		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}

	/**
	 * Update student education form (8)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateEducationForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the family form
		$form = EducationForm::find($student->educationFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}
		
		$form->question_1 = $request->input('question_1');
		$form->question_2 = $request->input('question_2');
		$form->question_3 = $request->input('question_3');
		$form->question_4 = $request->input('question_4');
		$form->question_5 = $request->input('question_5');
		$form->question_6 = $request->input('question_6');
		$form->question_7 = $request->input('question_7');
		$form->question_8 = $request->input('question_8');
		$form->question_9 = $request->input('question_9');

		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}

	/**
	 * Update student present form (9)
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updatePresentForm(Request $request, $studentId)
	{
		// Find the student
		$student = Student::find($studentId);
		if( !$student ) {
			return response()->json([
				'message' => "Unable to find student."
				], 404);
		}

		// Find the family form
		$form = PresentForm::find($student->presentFormId);
		if( !$form ) {
			return response()->json([
				'message' => "Unable to find form."
				], 404);
		}

		if( !$form->new ) {
			return response()->json([
				'message' => "Form was already updated, require admin permission to update once more."
				], 400);
		}
		
		$form->question_1 = $request->input('question_1');
		$form->question_2 = $request->input('question_2');
		$form->question_3 = $request->input('question_3');
		$form->question_4 = $request->input('question_4');

		$form->new 	= false;

		$form->save();

		return response()->json([
			'message' => "The form have been submitted successfully."
			], 200);
	}
}