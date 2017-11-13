<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class TeacherStudent extends Model 
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'teacherId', 'studentId'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];
}
