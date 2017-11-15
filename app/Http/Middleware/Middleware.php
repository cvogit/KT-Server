<?php

namespace App\Http\Middleware;

use App\Helpers\RequestHelper;
use Closure;
use Illuminate\Http\Request;

class Middleware
{
    /**
     * The req helper
     *
     * @var App\Helpers\RequestHelper
     */
    protected $req;

    public function __construct(RequestHelper $req, Request $request)
    {
        $this->req = $req;
        $this->req->setRequest($request);
    }
}
