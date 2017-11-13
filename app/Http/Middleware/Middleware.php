<?php

namespace App\Http\Middleware;

use App\Helpers\RequestHelper;
use Closure;

class ExampleMiddleware
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
