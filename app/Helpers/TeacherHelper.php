<?php

namespace App\Helpers;

use \Exception;
use Illuminate\Http\Request;

class TeacherHelper
{


	/**
	 * The request
	 *
	 * @var \Illuminate\Http\Request
	 */
	private $req;

	public function __construct(Request $request)
	{
		$this->req = $request;
	}

	/**
	 * Set object request to the current http request
	 *
	 * @param \Illuminate\Http\Request
	 */
	public function setRequest(Request $request)
	{
		$this->req = $request;
	}

	/**
	 * Validate {id}
	 *
	 * @param integer
	 *
	 * @return boolean
	 */
	public function validId($id)
	{
		if ( !is_numeric($id) || ($id < 1) )
			return false;
		return true;
	}

}