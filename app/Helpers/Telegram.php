<?php

namespace App\Helpers;

use App\Http\Requests\TelegramWebhook\TelegramWebhook;
use App\Models\TgMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class Telegram
{
    public $message;
    public $message_id;
    public $from;
    public $to;
    public $first_name;
    public $last_name;
    public $username;
    public $text;
    public $date;
    public $created_at;
    public $updated_at;
    public $stiker;
    public $reply_markup;

    public function __construct()
    {
        $this->message_id = 0;
        $this->from = 0;
        $this->to = 0;
        $this->text = 0;
        $this->date = 0;
        $this->created_at = 0;
        $this->updated_at = 0;
        $this->user_name = 0;
        $this->first_name = 0;
        $this->last_name = 0;

        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $input_data ]);

        //$this->get_message($input_data);
        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $this->message['text'] ]);

        //$this->log_request();

    }
/*
    public function show_message()
    {
        return $this->message;
    }
*/
    public function get_message(TelegramWebhook $request)
    {
        $out = [];
        $current_date = date('Y-m-d h:i:s', time());
        $out['current_date'] = $current_date;
        $this->created_at = $current_date;
        $this->updated_at = $current_date;
        $this->date = $request->message['date'];
        $this->user_name = (isset($request->message['chat']['username']) ? $request->message['chat']['username'] : '');
        $this->first_name = (isset($request->message['chat']['first_name']) ? $request->message['chat']['first_name'] : '');
        $this->last_name = (isset($request->message['chat']['last_name']) ? $request->message['chat']['last_name'] : '');
        $out['name_from'] = "{$this->user_name}_{$this->first_name}_{$this->last_name}";
        if (isset($request->callback_query)) {
            //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $request->callback_query['data'] ]);
            $this->message_id = $request->callback_query['message']['message_id'];
            $this->from = $request->callback_query['message']['chat']['id'];
            $this->to = $request->callback_query['message']['chat']['id'];
            $this->text = htmlspecialchars($request->callback_query['data']);
            $this->date = $request->callback_query['message']['date'];
            //$request->callback_query['message']['reply_markup']['inline_keyboard']
            //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $request->callback_query['data'] ]);
        } elseif (isset($request->message['reply_to_message'])) {
            //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $request->message ]);
            $this->message_id = $request->message['message_id'];
            $this->from = $request->message['chat']['id'];;
            if (isset($request->message['reply_to_message']['entities'])) {
                $this->to = $request->message['reply_to_message']['entities'][0]['user']['id'];
            } else {
                $this->to = $request->message['reply_to_message']['from']['id'];
            }
            $this->text = htmlspecialchars(isset($request->message['text']) ? $request->message['text'] : '1');
        } else {
            //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => $request->message ]);

            $this->message_id = $request->message['message_id'];
            $this->from = $request->message['chat']['id'];
            $this->to = $request->message['chat']['id'];
            $this->text = htmlspecialchars($request->message['text']);
            //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => 'not_callback_1' ]);

        }
        $out['name_href'] = "<a href=\"tg://user?id={$this->from}\">{$this->user_name}_{$this->first_name}_{$this->last_name}</a> ";
        return $out;
        //Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", ['chat_id' => 314136897, 'text' => $this ]);
    }

    public function log_request($message)
    {
        $tg_message = [];
        $tg_message['message_id'] = $message->message_id;
        $tg_message['from'] = $message->from;
        $tg_message['text'] = $message->text;
        $tg_message['date'] = $message->date;
        $tg_message['created_at'] = $message->created_at;
        $tg_message['updated_at'] = $message->updated_at;
        $tg_message['first_name'] = $message->first_name;
        $tg_message['last_name'] = $message->last_name;
        $tg_message['username'] = $message->username;
        $tg_message['to'] = $message->to;

        //Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", ['chat_id' => 314136897, 'text' => 'mid log']);
        DB::table('tg_messages')->insert($tg_message);
        //Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [ 'chat_id' => 314136897, 'text' => '---- lback_query!!!' ]);

    }
    public function sendMessage()
    {
        $send_params = [
            'chat_id' => $this->to,
            'text' => $this->text,
            'parse_mode' => 'html'
        ];
        if (isset($this->reply_markup)) {
            $send_params['reply_markup'] = $this->reply_markup;
        }
        $send_message = Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", $send_params);

        if ($send_message['ok']) {
            $this->log_request($this);
        }
    }
}
