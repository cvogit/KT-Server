<?php

namespace App\Helpers;

use App\User;
use Illuminate\Http\Request;

class UserHelpers
{
	public static function updateUser(User $user)
	{
		date_default_timezone_set('America/Los_Angeles');
		$user->lastLogin = date('m/d/Y h:i:s a');
		$user->save();
	}
}