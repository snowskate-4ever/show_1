<?php


namespace App\Services\Test;

use App\Helpers\Bot;
use App\Helpers\Telegram;
use App\Http\Requests\TelegramWebhook\TelegramWebhook;
use App\Models\TgChat;
use App\Models\TgMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class Service
{
    public function index(TelegramWebhook $request)
    {
        $bot = new Bot();
        echo "<pre>".print_r($bot->operators, true)."</pre>";
        echo "<pre>".print_r($bot->commands, true)."</pre>";

        $text = '
Привет! 
На связи ошмарный изобретатель Митяй!!!
Я тут пошалил и поломал базу. 
Нажми, пожалуйста, еще раз на 
/start 
Я немного переделал все и теперь можешь написать потестить связь через бота)
Сорян, что напрягаю.
Добавил менюшки с кнопочками.
Следующий этап добавление групп.
Ухахахаха! 
С Хэлоувином!';
        $clients = [
            '1292284723',
            '1866493951',
            '621381525',
            '893005225',
            '391365698',
            '231457145',
            '314136897',
        ];
        foreach ($clients as $client){
            //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => $client, 'text' => $text ]);
            sleep(2);
        }

    }
}
