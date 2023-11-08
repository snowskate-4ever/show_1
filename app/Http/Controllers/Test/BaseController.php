<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Services\Test\Service;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
