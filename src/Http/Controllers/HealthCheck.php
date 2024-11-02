<?php

namespace Appino\LaravelEureka\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class HealthCheck extends Controller
{
    public function healthCheck()
    {
        return Response::json(['status' => 'ok']);
    }
}
