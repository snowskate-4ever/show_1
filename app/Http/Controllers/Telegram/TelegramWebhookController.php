<?php

namespace App\Http\Controllers\Telegram;

use App\Helpers\Telegram;
use App\Http\Requests\TelegramWebhook\TelegramWebhook;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class TelegramWebhookController extends BaseController
{

    public function __invoke(TelegramWebhook $request)
    {
        $response = $this->service->index($request);

        //return $response;
    }
}
