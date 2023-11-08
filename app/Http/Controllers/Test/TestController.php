<?php

namespace App\Http\Controllers\Test;

use App\Http\Requests\TelegramWebhook\TelegramWebhook;
use App\Http\Requests\Test\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestController extends BaseController
{
    public function __invoke(TelegramWebhook $request)
    {
        $response = $this->service->index($request);

        return $response;
    }
}
