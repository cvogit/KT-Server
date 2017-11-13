<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class StudentReport extends Model 
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'teacherId', 'studentId', 'goals', 'results', 'score', 'notes', 'lastReport', 'nextReport', 'deadline', 'submitTime', 'weekOf'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];
}
