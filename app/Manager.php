<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model 
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id', 'userId'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
