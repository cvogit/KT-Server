<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if (Hash::check($request->input('password'), $user->password))
            return response()->json(['user' => $user->email]);

        return response()->json(['user_not_found'], 404);
    }
}