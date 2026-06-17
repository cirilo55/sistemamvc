<?php

namespace Sys\Http\Middleware;

use Sys\Http\Request;
use Sys\Http\Response;

interface Middleware
{
    public function handle(Request $request, callable $next): Response;
}
