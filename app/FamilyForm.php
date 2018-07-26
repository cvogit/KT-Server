<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class FamilyForm extends Model 
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'motherName', 'motherBirthday', 'motherEmployment', 'motherNationality', 'fatherName', 'fatherBirthday', 'fatherEmployment', 'fatherNationality', 'extraFamily', 'parentTogether', 'familyIllness', 'familyIllnessDetails'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];
}
