<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class Student extends Model 
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'avatarId', 'basicFormId', 'familyFormId', 'pregnancyFormId', 'birthFormId', 'infancyFormId', 'toddlerFormId', 'educationFormId','illnessFormId', 'presentFormId', 'active'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
