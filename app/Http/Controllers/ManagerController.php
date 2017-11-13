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
	 * The req helper
	 *
	 * @var App\Helpers\RequestHelper
	 */
	private $req;

	public function __construct(RequestHelper $req, Request $request)
	{
		$this->req = $req;
		$this->req->setRequest($request);
	}

}