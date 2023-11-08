<?php


namespace App\Services\Telegram;

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
        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $request->message ]);
        $operator = $bot->operators[0];

        $message = new Telegram();
        $params = $message->get_message($request);
        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $params ]);
        $check_tg_chat = $this->check_tg_chat($message);
        $params['isset_tg_user'] = $check_tg_chat['isset'];
        $params['account']['tg_chat'] = $check_tg_chat['tg_chat'];
        $params['message'] = $message;
        $params['current_date'] = date('Y-m-d h:i:s', time());
        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $message->text ]);

        if (in_array($message->text, $bot->commands ) ) {
            $params['func_name'] = substr($message->text, 1);
            //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $params ]);

            $params['bot_answer'] = $bot->make_answer($params);
            $message->text = $params['bot_answer']['answer_message_text'];
            $message->reply_markup = $params['bot_answer']['reply_markup'];
        } else {
            if (!in_array( $message->from, $bot->operators)) {
                $message->text = $params['name_href'] . ' : ' . $request->message['text'];
                $message->to = $operator;
            }
            //ttp::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $message->from ]);
            //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $message->to ]);
        }
        $message->sendMessage();

        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => 'end!!!' ]);
    }
    public function check_tg_chat ($message)
    {
        $tg_chat = DB::table('tg_chats')
            ->where('chat_id', '=', (string) $message->from)
            ->leftJoin('users', 'users.id', '=', 'tg_chats.user_id')
            ->get();
        $out = [];
        if (count($tg_chat) == 0) {
            $out['isset'] = false;
            $out['tg_chat'] = null;
            return $out;
        } else {
            $out['isset'] = true;
            $out['tg_chat'] = $tg_chat[0];
            return $out;
        }
    }
}
