<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a new controller instance.
     *
     * @return User
     */
    public function create(array $data)
    {
        return User::create([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function register(Request $request)
    {
        $data = $request->all();

        $validation = $this->validator($data);
        if($validation->fails())
            return response()->json(["message" => $validation->errors()], 500);

        $user = $this->create($data);
        return response()->json(['message' => "Registration successful, awaiting approval."], 200);
    }

    /**
     * Validate user inputs
     * @param array
     * @return boolean
     */
    public function validator(array $data)
    {

        return Validator::make($data, [
            'firstName' => 'required|string|max:64',
            'lastName'  => 'required|string|max:64',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
        ]);
    }
}