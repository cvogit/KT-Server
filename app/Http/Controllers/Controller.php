<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Controller extends BaseController
{
   /**
	 * The req helper
	 *
	 * @var App\Helpers\RequestHelper
	 */
	protected $req;

	public function __construct(Request $request)
	{
		$this->req = $request->get('req');
	}

}
