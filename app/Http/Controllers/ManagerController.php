<?php

namespace App\Http\Controllers;

use App\User;
use App\Helpers\RequestHelper;
use Auth;
use \Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Cvogit\LumenJWT\JWT;


class ManagerController extends Controller
{
	/**
	 * The JWT helper
	 *
	 * @var App\Helpers\JWTHelper
	 */
	private $jwt;

	public function __construct(RequestHelper $jwt, Request $request)
	{
		$this->jwt = $req;
		$this->jwt->setRequest($request);
	}

}